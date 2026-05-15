<?php
require_once __DIR__ . '/includes/layout.php';
admin_require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_validate_csrf()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'create_notification') {
        $title = trim(substr((string) ($_POST['title'] ?? ''), 0, 120));
        $description = trim(substr((string) ($_POST['description'] ?? ''), 0, 1000));

        if ($title === '' || $description === '') {
            admin_flash('error', 'Title and message are required.');
            admin_redirect('notifications.php');
        }

        $data = [
            'title' => $title,
            'description' => $description,
            'date' => date('Y-m-d'),
            'time' => date('h:i:s A'),
            'status' => 1,
            'is_active' => 1,
            'popup' => 1,
            'show_popup' => 1,
            'created_by' => $_SESSION['admin_id'] ?? 0,
        ];

        if (admin_insert_row('notification', $data)) {
            admin_flash('success', 'Notification added. Logged-in users will see it as a popup.');
        } else {
            admin_flash('error', 'Notification was not added. Please check the notification table columns.');
        }

        admin_redirect('notifications.php');
    }
}

$orderBy = admin_column_exists('notification', 'id') ? 'id DESC' : 'date DESC, time DESC';
$notifications = admin_fetch_all("SELECT * FROM notification ORDER BY {$orderBy} LIMIT 100");

admin_render_header('Notifications');
?>
<div class="content-grid">
    <section class="panel">
        <h2>Add Notification</h2>
        <form method="post" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?php echo e(admin_csrf_token()); ?>">
            <input type="hidden" name="action" value="create_notification">
            <label class="field">
                <span>Title</span>
                <input type="text" name="title" maxlength="120" required>
            </label>
            <label class="field" style="grid-column: 1 / -1;">
                <span>Message</span>
                <textarea name="description" maxlength="1000" required></textarea>
            </label>
            <button class="button primary" type="submit">Send Notification</button>
        </form>
    </section>

    <section class="panel">
        <h2>Popup Delivery</h2>
        <div class="detail-list">
            <div><span>Target</span><strong>All logged-in users</strong></div>
            <div><span>Display</span><strong>Popup plus notification list</strong></div>
            <div><span>Repeat</span><strong>Once per notification per browser</strong></div>
        </div>
    </section>
</div>

<section class="panel">
    <h2>Recent Notifications</h2>
    <?php if ($notifications) { ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notifications as $row) { ?>
                        <tr>
                            <td>#<?php echo e($row['id'] ?? '-'); ?></td>
                            <td><?php echo e($row['title'] ?? '-'); ?></td>
                            <td class="wrap"><?php echo e($row['description'] ?? '-'); ?></td>
                            <td><?php echo e(($row['date'] ?? '') . ' ' . ($row['time'] ?? '')); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { admin_empty_state('No notifications found.'); } ?>
</section>
<?php admin_render_footer(); ?>
