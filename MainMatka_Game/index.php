<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
include("include/functions.php");
app_restore_session_from_cookies();
//header("Cache-Control: max-age=31536000");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title> MainMatka | ONLINE MATKA PLAY APP </title>
    <meta name="title" content="MainMatka | PLAY ONLINE MATKA | SATTA MATKA PLAY">
    <meta name="description" content="MainMatka App Experience with new Online Matka Play App website and MainMatka in Satta Matka Play online matka Industry 2023 with MainMatka.">
    <link rel="canonical" href="https://mainmatka.app" />
    <?php include("include/head.php"); ?>
    <style>
        /* ===== Home game cards (dark row + clock popup + golden play) ===== */

        .game-card-new {
            display: flex;
            align-items: stretch;
            gap: 12px;
            background: linear-gradient(145deg, #0f1f3d 0%, #0a1528 55%, #0c1830 100%);
            border-radius: 16px;
            padding: 12px 12px 12px 10px;
            margin-bottom: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .35);
            border: 1px solid rgba(255, 255, 255, .06);
        }

        .game-card__clock {
            flex: 0 0 auto;
            align-self: center;
            width: 48px;
            height: 48px;
            padding: 0;
            border: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, .08);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .2s, transform .15s;
        }

        .game-card__clock:hover,
        .game-card__clock:focus {
            background: rgba(232, 184, 74, .2);
            outline: none;
        }

        .game-card__clock:active {
            transform: scale(.96);
        }

        .game-card__clock img {
            width: 30px;
            height: 30px;
            object-fit: contain;
            display: block;
        }

        .game-card__main {
            flex: 1 1 auto;
            min-width: 0;
            text-align: left;
        }

        .game-card__title-row {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        .game-title {
            font-size: 15px;
            font-weight: 700;
            color: #f6ad55;
            letter-spacing: .02em;
            margin: 0;
            line-height: 1.2;
        }

        .game-card__info {
            padding: 0;
            border: none;
            background: transparent;
            color: #f6ad55;
            font-size: 14px;
            line-height: 1;
            cursor: pointer;
            opacity: .85;
        }

        .game-card__info:hover {
            opacity: 1;
        }

        .game-result {
            font-size: 17px;
            font-weight: 700;
            color: #93c5fd;
            letter-spacing: .06em;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .game-card__meta {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255, 255, 255, .45);
            letter-spacing: .02em;
            line-height: 1.35;
            margin-bottom: 4px;
            color: darkgoldenrod;
        }

        .status-badge {
            display: inline-block;
            margin-top: 2px;
            padding: 3px 0 0;
            font-size: 11px;
            font-weight: 700;
            border-radius: 0;
            background: transparent !important;
        }

        .status-badge.running {
            color: #68d391;
        }

        .status-badge.closed {
            color: #fc8181;
        }

        .game-card__play {
            flex: 0 0 auto;
            align-self: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 92px;
            padding: 10px 14px;
            border-radius: 12px;
            text-decoration: none !important;
            background: linear-gradient(180deg, #f0d27a 0%, #d4a84a 45%, #b8893a 100%);
            color: #fff !important;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .03em;
            text-shadow: 0 1px 0 rgba(0, 0, 0, .15);
            box-shadow: 0 4px 12px rgba(184, 137, 58, .45), inset 0 1px 0 rgba(255, 255, 255, .35);
            border: 1px solid rgba(255, 255, 255, .25);
            transition: transform .15s, filter .15s;
        }

        .game-card__play:hover {
            color: #fff !important;
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .game-card__play img {
            width: 22px;
            height: 22px;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 1px 1px rgba(0, 0, 0, .2));
        }

        .game-card__play--disabled,
        .game-card__play--disabled:hover {
            pointer-events: none;
            cursor: not-allowed;
            opacity: .42;
            filter: grayscale(.35);
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 360px) {
            .game-result {
                font-size: 15px;
            }

            .game-title {
                font-size: 14px;
            }

            .game-card__play {
                min-width: 84px;
                padding: 9px 10px;
                font-size: 13px;
                gap: 6px;
            }

            .game-card__play img {
                width: 20px;
                height: 20px;
            }

            .game-card__clock {
                width: 44px;
                height: 44px;
            }

            .game-card__clock img {
                width: 26px;
                height: 26px;
            }
        }

        /* Game timings popup � centered on screen, gold header like ref (#f0a500 ? #b36b00) */
        .game-time-modal.modal.fade .modal-dialog {
            transform: none !important;
            transition: opacity .2s linear;
        }

        .game-time-modal.modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .game-time-modal .modal-dialog {
            max-width: 340px;
            width: calc(100% - 1rem);
            margin: auto;
            display: flex;
            align-items: center;
        }

        .game-time-modal .modal-content {
            background: #050a1a;
            border: none;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 16px 48px rgba(0, 0, 0, .55);
            width: 100%;
        }

        .game-time-modal .modal-header.game-time-modal__header {
            background: linear-gradient(180deg, #DC8110 0%, #A55B09 100%) !important;
            background-image: linear-gradient(180deg, #DC8110 0%, #A55B09 100%) !important;
            border: none;
            padding: 14px 42px 14px 16px;
            position: relative;
            align-items: center;
            min-height: 52px;
        }

        .game-time-modal__title {
            margin: 0 auto;
            width: 100%;
            text-align: center;
            color: #fff !important;
            font-weight: 700;
            font-size: 17px;
            letter-spacing: .02em;
            line-height: 1.25;
        }

        .game-time-modal__close {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(calc(-50% + 8px));
            margin: 0;
            padding: 6px 10px 8px;
            color: #fff !important;
            opacity: 1;
            text-shadow: none;
            font-size: 26px;
            font-weight: 300;
            line-height: 1;
        }

        .game-time-modal__close:hover {
            color: #fff !important;
            opacity: .85;
        }

        .game-time-modal .modal-body.game-time-modal__body {
            background: #050a1a;
            padding: 0;
        }

        .game-time-modal__row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-bottom: 1px solid rgba(163, 93, 0, .45);
        }

        .game-time-modal__row:last-child {
            border-bottom: none;
        }

        .game-time-modal__ico {
            flex: 0 0 40px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .35);
            background: rgba(0, 0, 0, .25);
            padding: 2px;
        }

        .game-time-modal__ico img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .game-time-modal__label {
            flex: 1 1 auto;
            min-width: 0;
            color: #ff9900;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: .01em;
        }

        .game-time-modal__value {
            flex: 0 0 auto;
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
        }

        .game-time-modal .modal-footer.game-time-modal__footer {
            background: #050a1a;
            border: none;
            padding: 14px 16px 18px;
            justify-content: stretch;
        }

        .game-time-modal__ok {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-weight: 700;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
            background: linear-gradient(180deg, #DC8110 0%, #A55B09 100%) !important;
            background-image: linear-gradient(180deg, #DC8110 0%, #A55B09 100%) !important;
            box-shadow: 0 4px 14px rgba(165, 91, 9, .5);
            transition: filter .15s, transform .1s;
        }

        .game-time-modal__ok:hover {
            color: #fff;
            filter: brightness(1.06);
        }

        .game-time-modal__ok:active {
            transform: scale(.99);
        }
    </style>
</head>

<body class="page-home">

    <div class="wrapper">

        <?php include("include/sidebar.php"); ?>
        <div id="content">
            <?php include("include/nav.php"); ?>

            <section class="home-hero" aria-label="Play now">
                <div class="home-hero__card">
                    <img class="home-hero__bg" src="assets/img/hero_home.png" alt="" width="800" height="450" fetchpriority="low" decoding="async">
                    <div class="home-hero__body">
                        <h2 class="home-hero__title">Let&rsquo;s Start Playing Now</h2>
                        <p class="home-hero__sub">Chose your favourite game</p>
                        <div class="home-hero__actions">
                            <a href="javascript:void(0)" class="home-hero__btn-starline" style="opacity:0.5;cursor:not-allowed;">Starline</a>
                            <a href="add-fund.php" class="home-hero__btn-deposit">Deposit</a>
                        </div>
                    </div>
                </div>
            </section>

            <div class="notice-bar-new">
                <div class="notice-bar-new__label">NEWS</div>
                <div class="notice-bar-new__content">
                    <div class="notice-bar-new__track">
                        <span><?php echo get_SettingValue('app_notice'); ?></span>
                    </div>
                </div>
            </div>


            <div class="container text-center">
                <div style="padding:10px 0 5px;">
                    <input type="text" id="marketSearch" placeholder="🔍 Search market name..." autocomplete="off" style="width:100%;padding:10px 16px;border-radius:10px;border:1px solid rgba(232,184,74,.3);background:rgba(10,27,54,.95);color:#fff;font-size:14px;outline:none;" onfocus="this.style.borderColor='rgba(232,184,74,.7)'" onblur="this.style.borderColor='rgba(232,184,74,.3)'">
                </div>
                <div class="tb-10" id="marketList">

                     <?php
                    $games_list_qry =  "SELECT * FROM `parent_games` WHERE status=1 order by order_of_display";
                    $games = mysqli_query($con, $games_list_qry);
                    while ($row = mysqli_fetch_array($games)) {
                        $open_time =  $row['open_time'];
                        $close_time = $row['close_time'];
                        $result_open_time = $row['result_open_time'];
                        $result_close_time = $row['result_close_time'];
                        $open_days = $row['open_days'];
                        $game_days = explode(",", $open_days);

                        $day = strtolower(date('D', strtotime(date('Y-m-d'))));

                        $betting_open_time = strtotime(date('Y-m-d') . ' ' . $open_time);
                        $betting_close_time = strtotime(date('Y-m-d') . ' ' . $close_time);


                        if (in_array($day, $game_days) && time() < $betting_open_time) {
                            $bidding_status = 1;
                            $msg = 'Betting is Running Now';
                            $default_bidding_date = 'today';
                            $default_bidding_game = 'open';
                        } elseif (in_array($day, $game_days) && time() < $betting_close_time) {
                            $bidding_status = 1;
                            $msg = 'Betting is Running For Close';
                            $default_bidding_date = 'today';
                            $default_bidding_game = 'close';
                        } else {
                            $bidding_status = 0;
                            $msg = 'Betting is Closed for Today';
                            $default_bidding_date = 'next_date';
                            $default_bidding_game = 'open';
                        }


                        $child_open = $row['child_open_id'];
                        $child_close = $row['child_close_id'];
                        $open_result = GetOpneResultByid($child_open);
                        $close_result = GetCloseResultByid($child_close);

                        $game_id = $row['id'];
                        $game_name = $row['name'];
                        $open_time = date("h:i A", strtotime($open_time));
                        $close_time = date("h:i A", strtotime($close_time));
                        $result_open_time = date("h:i A", strtotime($result_open_time));
                        $result_close_time = date("h:i A", strtotime($result_close_time));
                        $result = $open_result . '' . $close_result;
                        $bidding_status = $bidding_status;
                        $msg =  $msg;
                        $default_bidding_date = $default_bidding_date;
                        $default_bidding_game = $default_bidding_game;
                        $status = $row['status'];
                        $game_title = strtolower(str_replace(" ", "-", $game_name));

                    ?>

                        <div class="game-card-new">

                            <button type="button" class="game-card__clock" data-toggle="modal" data-target="#gameTimeModal<?php echo $game_id; ?>" aria-label="Timings for <?php echo htmlspecialchars($game_name, ENT_QUOTES, 'UTF-8'); ?>">
                                <img src="assets/img/clock.png" width="30" height="30" alt="">
                            </button>

                            <div class="game-card__main">
                                <div class="game-card__title-row">
                                    <span class="game-title"><?php echo htmlspecialchars($game_name, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <button type="button" class="game-card__info" data-toggle="modal" data-target="#gameTimeModal<?php echo $game_id; ?>" aria-label="Info"><i class="fa fa-info-circle"></i></button>
                                </div>
                                <div class="game-result"><?php echo $result; ?></div>
                                <div class="game-card__meta"> <?php echo $open_time; ?> &nbsp;|&nbsp;  <?php echo $close_time; ?></div>
                                <?php if ($bidding_status) { ?>
                                    <div class="status-badge running"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
                                <?php } else { ?>
                                    <div class="status-badge closed"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
                                <?php } ?>
                            </div>

                            <?php if ($bidding_status) { ?>
                                <a href="game-dashboard.php?game=<?php echo $game_title; ?>&gid=<?php echo $game_id; ?>" class="game-card__play">
                                    <img src="assets/img/play.png" width="22" height="22" alt="">
                                    <span>Play</span>
                                </a>
                            <?php } else { ?>
                                <span class="game-card__play game-card__play--disabled" role="text" aria-disabled="true">
                                    <img src="assets/img/play.png" width="22" height="22" alt="">
                                    <span>Play</span>
                                </span>
                            <?php } ?>

                        </div>


                        <div class="modal fade game-time-modal" id="gameTimeModal<?php echo $game_id; ?>" tabindex="-1" role="dialog" aria-labelledby="gameTimeModalLabel<?php echo $game_id; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header game-time-modal__header">
                                        <h5 class="modal-title game-time-modal__title" id="gameTimeModalLabel<?php echo $game_id; ?>"><?php echo htmlspecialchars($game_name, ENT_QUOTES, 'UTF-8'); ?></h5>
                                        <button type="button" class="close game-time-modal__close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body game-time-modal__body">
                                        <div class="game-time-modal__row">
                                            <span class="game-time-modal__ico"><img src="assets/img/clock.png" width="40" height="40" alt=""></span>
                                            <span class="game-time-modal__label">Open Bid Ends</span>
                                            <span class="game-time-modal__value"><?php echo htmlspecialchars($open_time, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <div class="game-time-modal__row">
                                            <span class="game-time-modal__ico"><img src="assets/img/clock1.png" width="40" height="40" alt=""></span>
                                            <span class="game-time-modal__label">Close Bid Ends</span>
                                            <span class="game-time-modal__value"><?php echo htmlspecialchars($close_time, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <div class="game-time-modal__row">
                                            <span class="game-time-modal__ico"><img src="assets/img/clock.png" width="40" height="40" alt=""></span>
                                            <span class="game-time-modal__label">Open Result</span>
                                            <span class="game-time-modal__value"><?php echo htmlspecialchars($result_open_time, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <div class="game-time-modal__row">
                                            <span class="game-time-modal__ico"><img src="assets/img/clock1.png" width="40" height="40" alt=""></span>
                                            <span class="game-time-modal__label">Close Result</span>
                                            <span class="game-time-modal__value"><?php echo htmlspecialchars($result_close_time, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer game-time-modal__footer">
                                        <button type="button" class="game-time-modal__ok" data-dismiss="modal">OK</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    <?php } ?>

                    <?php
                    // Show scraped markets from dpboss
                    include_once(__DIR__ . '/include/scraped-markets.php');
                    render_scraped_markets();
                    ?>

                </div>
            </div>
            <br><br><br>


        </div>
    </div>

    <?php if (1) { ?>
        <div class="modal fade modern-popup" id="noticeboard" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Official Android App</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="popup-image-centered">
                            <img src="assets/img/app-home.webp?v1" class="img-fluid" alt="MainMatka App">
                        </div>
                        <div class="popup-content-centered">
                            <h3>GET OUR APP</h3>
                            <p>Experience the ultimate gaming lounge with our official app. Fast, secure, and packed with exclusive features.</p>
                            <a href="https://mainmatka.app/apk/MainMatka_1011.apk" class="popup-btn-centered">
                                <i class="fa fa-download"></i> Download Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php include("include/footer.php"); ?>

    <script>
    (function(){
        var search = document.getElementById('marketSearch');
        if (!search) return;
        search.addEventListener('input', function(){
            var q = this.value.toLowerCase().trim();
            var cards = document.querySelectorAll('#marketList .game-card-new');
            cards.forEach(function(card){
                var name = card.querySelector('.game-title');
                if (!name) return;
                if (q === '' || name.textContent.toLowerCase().indexOf(q) !== -1) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    })();
    </script>

    <div class="wts-flt-btn">

        <a href="https://wa.me/<?php echo get_SettingValue('PWA_whatsapp2'); ?>"><i class="fa fa-whatsapp"></i>
            <!--
<a href="https://t.me/" ><i class="fa fa-telegram"></i> 
-->
    </div>


    <script type="text/javascript">
        $(document).ready(function() {
            // Check if the cookie exists and has a value of 'seen'
            if (document.cookie.indexOf('popup_seen') === -1) {
                // If the cookie doesn't exist, show the popup
                $('#noticeboard').modal('show');
            }

            // Set a cookie to indicate that the user has seen the popup
            $('#noticeboard').on('hidden.bs.modal', function() {
                var date = new Date();
                date.setTime(date.getTime() + (3 * 60 * 60 * 1000)); // Set the cookie to expire in 6 hours
                document.cookie = 'popup_seen=true; expires=' + date.toUTCString() + '; path=/';
            });
        });
    </script>



</body>

</html>
