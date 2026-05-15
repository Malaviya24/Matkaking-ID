<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="icon" type="image/png" sizes="192x192"  href="assets/icons/196.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="assets/icons/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">

<meta name="apple-mobile-web-app-title" content="Add to Home">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="OnlineMatkaPlay">

<?php if(0){?>
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
<script>
  window.OneSignalDeferred = window.OneSignalDeferred || [];
  OneSignalDeferred.push(function(OneSignal) {
    OneSignal.init({
      appId: "a82d0b2b-7489-111111",
    });
  });
</script>
<?php } ?>
<style>
 
:root{--blue:#007bff;--indigo:#6610f2;--purple:#6f42c1;--pink:#e83e8c;--red:#dc3545;--orange:#fd7e14;--yellow:#ffc107;--green:#28a745;--teal:#20c997;--cyan:#17a2b8;--white:#fff;--gray:#6c757d;--gray-dark:#343a40;--primary:#007bff;--secondary:#6c757d;--success:#28a745;--info:#17a2b8;--warning:#ffc107;--danger:#dc3545;--light:#f8f9fa;--dark:#343a40;--breakpoint-xs:0;--breakpoint-sm:576px;--breakpoint-md:768px;--breakpoint-lg:992px;--breakpoint-xl:1200px;--font-family-sans-serif:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";--font-family-monospace:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace}*,::after,::before{box-sizing:border-box}html{font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;-ms-overflow-style:scrollbar;-webkit-tap-highlight-color:transparent}@-ms-viewport{width:device-width}nav{display:block}body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#fff}h3{margin-top:0;margin-bottom:.5rem}p{margin-top:0;margin-bottom:1rem}ul{margin-top:0;margin-bottom:1rem}a{color:#007bff;text-decoration:none;background-color:transparent;-webkit-text-decoration-skip:objects}a:hover{color:#0056b3;text-decoration:underline}a:not([href]):not([tabindex]){text-decoration:none}a:not([href]):not([tabindex]):focus,a:not([href]):not([tabindex]):hover{text-decoration:none}a:not([href]):not([tabindex]):focus{outline:0}img{vertical-align:middle;border-style:none}button{border-radius:0}button:focus{outline:1px dotted;outline:5px auto -webkit-focus-ring-color}button{margin:0;font-family:inherit;font-size:inherit;line-height:inherit}button{overflow:visible}button{text-transform:none}button,html [type=button]{-webkit-appearance:button}[type=button]::-moz-focus-inner,button::-moz-focus-inner{padding:0;border-style:none}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button}h3{margin-bottom:.5rem;font-family:inherit;font-weight:500;line-height:1.2;color:inherit}h3{font-size:1.75rem}.list-unstyled{padding-left:0;list-style:none}.container{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}@media (min-width:576px){.container{max-width:540px}}@media (min-width:768px){.container{max-width:720px}}@media (min-width:992px){.container{max-width:960px}}@media (min-width:1200px){.container{max-width:1140px}}.container-fluid{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}.row{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}.col-12,.col-3,.col-4,.col-6{position:relative;width:100%;min-height:1px;padding-right:15px;padding-left:15px}.col-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.btn{display:inline-block;font-weight:400;text-align:center;white-space:nowrap;vertical-align:middle;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;border:1px solid transparent;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:.25rem;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out}@media screen and (prefers-reduced-motion:reduce){.btn{transition:none}}.btn:focus,.btn:hover{text-decoration:none}.btn:focus{outline:0;box-shadow:0 0 0 .2rem rgba(0,123,255,.25)}.btn.disabled,.btn:disabled{opacity:.65}.btn:not(:disabled):not(.disabled){cursor:pointer}.btn:not(:disabled):not(.disabled).active,.btn:not(:disabled):not(.disabled):active{background-image:none}a.btn.disabled{pointer-events:none}.btn-primary:not(:disabled):not(.disabled).active,.btn-primary:not(:disabled):not(.disabled):active{color:#fff;background-color:#0062cc;border-color:#005cbf}.btn-primary:not(:disabled):not(.disabled).active:focus,.btn-primary:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(0,123,255,.5)}.btn-secondary:not(:disabled):not(.disabled).active,.btn-secondary:not(:disabled):not(.disabled):active{color:#fff;background-color:#545b62;border-color:#4e555b}.btn-secondary:not(:disabled):not(.disabled).active:focus,.btn-secondary:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(108,117,125,.5)}.btn-success:not(:disabled):not(.disabled).active,.btn-success:not(:disabled):not(.disabled):active{color:#fff;background-color:#1e7e34;border-color:#1c7430}.btn-success:not(:disabled):not(.disabled).active:focus,.btn-success:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(40,167,69,.5)}.btn-info:not(:disabled):not(.disabled).active,.btn-info:not(:disabled):not(.disabled):active{color:#fff;background-color:#117a8b;border-color:#10707f}.btn-info:not(:disabled):not(.disabled).active:focus,.btn-info:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(23,162,184,.5)}.btn-warning:not(:disabled):not(.disabled).active,.btn-warning:not(:disabled):not(.disabled):active{color:#212529;background-color:#d39e00;border-color:#c69500}.btn-warning:not(:disabled):not(.disabled).active:focus,.btn-warning:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(255,193,7,.5)}.btn-danger:not(:disabled):not(.disabled).active,.btn-danger:not(:disabled):not(.disabled):active{color:#fff;background-color:#bd2130;border-color:#b21f2d}.btn-danger:not(:disabled):not(.disabled).active:focus,.btn-danger:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(220,53,69,.5)}.btn-light:not(:disabled):not(.disabled).active,.btn-light:not(:disabled):not(.disabled):active{color:#212529;background-color:#dae0e5;border-color:#d3d9df}.btn-light:not(:disabled):not(.disabled).active:focus,.btn-light:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(248,249,250,.5)}.btn-dark:not(:disabled):not(.disabled).active,.btn-dark:not(:disabled):not(.disabled):active{color:#fff;background-color:#1d2124;border-color:#171a1d}.btn-dark:not(:disabled):not(.disabled).active:focus,.btn-dark:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(52,58,64,.5)}.btn-outline-primary:not(:disabled):not(.disabled).active,.btn-outline-primary:not(:disabled):not(.disabled):active{color:#fff;background-color:#007bff;border-color:#007bff}.btn-outline-primary:not(:disabled):not(.disabled).active:focus,.btn-outline-primary:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(0,123,255,.5)}.btn-outline-secondary:not(:disabled):not(.disabled).active,.btn-outline-secondary:not(:disabled):not(.disabled):active{color:#fff;background-color:#6c757d;border-color:#6c757d}.btn-outline-secondary:not(:disabled):not(.disabled).active:focus,.btn-outline-secondary:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(108,117,125,.5)}.btn-outline-success:not(:disabled):not(.disabled).active,.btn-outline-success:not(:disabled):not(.disabled):active{color:#fff;background-color:#28a745;border-color:#28a745}.btn-outline-success:not(:disabled):not(.disabled).active:focus,.btn-outline-success:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(40,167,69,.5)}.btn-outline-info:not(:disabled):not(.disabled).active,.btn-outline-info:not(:disabled):not(.disabled):active{color:#fff;background-color:#17a2b8;border-color:#17a2b8}.btn-outline-info:not(:disabled):not(.disabled).active:focus,.btn-outline-info:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(23,162,184,.5)}.btn-outline-warning:not(:disabled):not(.disabled).active,.btn-outline-warning:not(:disabled):not(.disabled):active{color:#212529;background-color:#ffc107;border-color:#ffc107}.btn-outline-warning:not(:disabled):not(.disabled).active:focus,.btn-outline-warning:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(255,193,7,.5)}.btn-outline-danger:not(:disabled):not(.disabled).active,.btn-outline-danger:not(:disabled):not(.disabled):active{color:#fff;background-color:#dc3545;border-color:#dc3545}.btn-outline-danger:not(:disabled):not(.disabled).active:focus,.btn-outline-danger:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(220,53,69,.5)}.btn-outline-light:not(:disabled):not(.disabled).active,.btn-outline-light:not(:disabled):not(.disabled):active{color:#212529;background-color:#f8f9fa;border-color:#f8f9fa}.btn-outline-light:not(:disabled):not(.disabled).active:focus,.btn-outline-light:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(248,249,250,.5)}.btn-outline-dark:not(:disabled):not(.disabled).active,.btn-outline-dark:not(:disabled):not(.disabled):active{color:#fff;background-color:#343a40;border-color:#343a40}.btn-outline-dark:not(:disabled):not(.disabled).active:focus,.btn-outline-dark:not(:disabled):not(.disabled):active:focus{box-shadow:0 0 0 .2rem rgba(52,58,64,.5)}.navbar{position:relative;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;padding:.5rem 1rem}.navbar>.container-fluid{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between}.navbar-toggler:not(:disabled):not(.disabled){cursor:pointer}@media (max-width:991.98px){.navbar-expand-lg>.container-fluid{padding-right:0;padding-left:0}}@media (min-width:992px){.navbar-expand-lg{-ms-flex-flow:row nowrap;flex-flow:row nowrap;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand-lg>.container-fluid{-ms-flex-wrap:nowrap;flex-wrap:nowrap}}.page-link:not(:disabled):not(.disabled){cursor:pointer}.close{float:right;font-size:1.5rem;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;opacity:.5}.close:focus,.close:hover{color:#000;text-decoration:none;opacity:.75}.close:not(:disabled):not(.disabled){cursor:pointer}button.close{padding:0;background-color:transparent;border:0;-webkit-appearance:none}.modal{background: #000000ad;position:fixed;top:0;right:0;bottom:0;left:0;z-index:1050;display:none;overflow:hidden;outline:0}.modal-dialog{position:relative;width:auto;margin:130px auto;pointer-events:none}.modal-content{position:relative;display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;width:100%;pointer-events:auto;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.2);border-radius:.3rem;outline:0}.modal-header{display:-ms-flexbox;display:flex;-ms-flex-align:start;align-items:flex-start;-ms-flex-pack:justify;justify-content:space-between;padding:1rem;border-bottom:1px solid #e9ecef;border-top-left-radius:.3rem;border-top-right-radius:.3rem}.modal-header .close{padding:1rem;margin:-1rem -1rem -1rem auto}.modal-title{margin-bottom:0;line-height:1.5}.modal-body{position:relative;-ms-flex:1 1 auto;flex:1 1 auto;padding:1rem}.modal-footer{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-ms-flex-pack:end;justify-content:flex-end;padding:1rem;border-top:1px solid #e9ecef}@media (min-width:576px){.modal-dialog{max-width:500px;margin: 200px auto;}}@supports ((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){}@supports ((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){}@supports ((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){}@supports ((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){}.d-inline-block{display:inline-block!important}@supports ((position:-webkit-sticky) or (position:sticky)){}.ml-auto{margin-left:auto!important}.text-center{text-align:center!important}@media print{*,::after,::before{text-shadow:none!important;box-shadow:none!important}a:not(.btn){text-decoration:underline}img{page-break-inside:avoid}h3,p{orphans:3;widows:3}h3{page-break-after:avoid}@page{size:a3}body{min-width:992px!important}.container{min-width:992px!important}.navbar{display:none}}
.form-control{display:block;width:100%;padding:.375rem .75rem;font-size:1rem;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out }@media screen and (prefers-reduced-motion:reduce){.form-control{transition:none }}.form-control::-ms-expand{background-color:transparent;border:0 }.form-control:focus{color:#495057;background-color:#fff;border-color:#80bdff;outline:0;box-shadow:0 0 0 .2rem rgba(0,123,255,.25) }.form-control::-webkit-input-placeholder{color:#6c757d;opacity:1 }.form-control::-moz-placeholder{color:#6c757d;opacity:1 }.form-control:-ms-input-placeholder{color:#6c757d;opacity:1 }.form-control::-ms-input-placeholder{color:#6c757d;opacity:1 }.form-control:disabled{background-color:#e9ecef;opacity:1 }.form-group{margin-bottom:1rem }

/* Auth screens (Login / Register) - common CSS (mobile-first) */
body.auth-screen{
  background-color:#050208!important;
  background-image:url('assets/img/backgound.jpeg')!important;
  background-repeat:no-repeat!important;
  background-position:center center!important;
  background-size:cover!important;
  background-attachment:scroll!important;
  color:#fff!important;
  min-height:100vh!important;
  padding-bottom:0!important;
}
body.auth-screen:before{
  content:""!important;
  position:fixed!important;
  inset:0!important;
  background:linear-gradient(180deg,rgba(0,0,0,.5) 0%,rgba(0,0,0,.28) 42%,rgba(0,0,0,.62) 100%),
    radial-gradient(ellipse 130% 70% at 50% -10%,rgba(202,166,74,.14),transparent 52%)!important;
  pointer-events:none!important;
  z-index:0!important;
}

/* Login/register: hide header (nav), sidebar, bottom footer bar */
body.auth-screen #sidebar,
body.auth-screen nav.navbar,
body.auth-screen #footer-bar,
body.auth-screen .wts-flt-btn,
body.auth-screen .overlay{display:none!important}
body.auth-screen .wrapper,
body.auth-screen #content{margin:0!important;padding:0!important;width:100%!important}
body.auth-screen #content{position:relative;z-index:1}

/* Auth card layout */
.auth-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:28px 20px}
.auth-panel{width:100%;max-width:420px;margin:0 auto;background:transparent!important;border:0!important;box-shadow:none!important;border-radius:0!important;padding:0!important;position:relative;z-index:1}
.auth-head{margin-bottom:18px}
.auth-kicker{font-size:15px;color:#fff!important;opacity:1;margin:20px 0 6px 0;font-weight:600;letter-spacing:.02em}
.auth-logo{width:64px;height:64px;border-radius:18px;object-fit:cover;display:block;margin:0 0 10px 0;border:1px solid rgba(202,166,74,.35)}
.auth-brand{font-size:26px;font-weight:800;letter-spacing:.2px;margin:0}
.auth-brand span{color:#caa64a}

/* Fields */
.auth-label{font-size:12px;color:#fff!important;opacity:1;margin:14px 0 8px;display:block}
body.auth-screen .input-group{border:1px solid rgba(202,166,74,.75);border-radius:14px;overflow:hidden;background:rgba(0,0,0,.55)}
body.auth-screen .input-group-text{background:transparent!important;border:0!important;color:#caa64a!important;padding:14px 14px}
body.auth-screen .form-control{background:transparent!important;border:0!important;color:#fff!important;height:auto;padding:14px 14px}
body.auth-screen .form-control::placeholder{color:rgba(255,255,255,.55)}
body.auth-screen .form-control:focus{box-shadow:none!important}
.auth-row{display:flex;align-items:center;justify-content:flex-end;margin-top:10px}
.auth-link{color:#caa64a!important;font-size:12px;text-decoration:none}
.auth-link:hover{text-decoration:underline}

/* Buttons & footer */
.auth-btn{margin:14px auto 0 auto;border-radius:14px!important;padding:12px 16px!important;font-weight:800!important;text-transform:none!important;letter-spacing:0!important;background:linear-gradient(180deg,#f0d27a,#caa64a)!important;color:#1a1200!important;box-shadow:0 4px 12px rgba(202,166,74,.22)!important;width:72%!important;display:block}
.auth-btn:active{transform:translateY(1px)}
.auth-foot{margin-top:18px;font-size:12px;opacity:.9;text-align:center}
.auth-foot a{color:#caa64a!important;font-weight:800}

/* Global body background (all pages) */
body{background:#000!important}

</style>

<link rel="stylesheet" href="assets/css/app-modern.css?v=1.0.3">

<style>
/* Navbar (after app-modern): #0A1B36 strip vs body #000, slight lift + shadow, wallet PNG */
:root{
  --nav-bg:#0A1B36;
  --nav-gold:#e8b84a;
}
nav.navbar{
  background:var(--nav-bg)!important;
  background-image:none!important;
  border-radius:0 0 16px 16px!important;
  margin-top:0!important;
  padding-top:calc(8px + env(safe-area-inset-top, 0px))!important;
  padding-bottom:10px!important;
  padding-left:14px!important;
  padding-right:14px!important;
  box-shadow:0 4px 12px rgba(0,0,0,.25)!important;
  border-bottom:1px solid rgba(10,27,54,.9);
  position:relative;
  z-index:101;
  overflow:hidden;
}
nav.navbar::after{display:none!important;content:none!important}
nav.navbar .container-fluid{
  gap:8px;
}
nav.navbar .menu-btn i{color:#fff!important}
nav.navbar > .container-fluid > .nav-brand-home{
  font-size:0!important;
  color:transparent!important;
  white-space:nowrap;
  vertical-align:middle;
  text-decoration:none!important;
  display:inline-flex;
  align-items:center;
}
nav.navbar > .container-fluid > .nav-brand-home::before{
  content:"MainMatka";
  font-size:1.12rem!important;
  font-weight:700!important;
  color:#fff!important;
  letter-spacing:.02em;
}
/* nav.navbar > .container-fluid > span::after{
  content:"Matka";
  font-size:1.12rem!important;
  font-weight:700!important;
  color:var(--nav-gold)!important;
  letter-spacing:.02em;
} */
nav.navbar .btn-white[href*="add-fund.php"]{
  background:transparent!important;
  border:2px solid var(--nav-gold)!important;
  border-radius:10px!important;
  padding:10px 12px 8px!important;
  box-shadow:none!important;
  gap:10px!important;
  color:var(--nav-gold)!important;
  display:inline-flex!important;
  align-items:center!important;
  line-height:1!important;
}
nav.navbar .btn-white[href*="add-fund.php"] .walletamt{
  display:inline-flex!important;
  align-items:center!important;
  line-height:1!important;
}
nav.navbar .btn-white[href*="add-fund.php"] .walletamt,
nav.navbar .btn-white[href*="add-fund.php"] .walletamt i{
  color:var(--nav-gold)!important;
}
/* Replace FontAwesome money icon with local PNG (no nav.php change) */
nav.navbar .btn-white[href*="add-fund.php"] .fa-money{
  display:inline-block!important;
  width:22px!important;
  height:22px!important;
  min-width:22px!important;
  font-size:0!important;
  line-height:0!important;
  color:transparent!important;
  vertical-align:middle!important;
  flex-shrink:0!important;
  align-self:center!important;
  transform:translateY(-3px)!important;
  background:url("assets/icons/wallet.png") center center / contain no-repeat!important;
}
/* Guest login button: subtle outline, readable on black */
nav.navbar .btn-white[href*="login.php"]{
  background:rgba(255,255,255,.06)!important;
  border:1px solid rgba(232,184,74,.45)!important;
  border-radius:10px!important;
  color:#fff!important;
  box-shadow:none!important;
}

/* Bottom footer bar — same background as navbar (#0A1B36) */
#footer-bar{
  background:#0A1B36!important;
  background-color:#0A1B36!important;
  box-shadow:0 -3px 10px rgba(0,0,0,.25)!important;
  border-top:1px solid rgba(255,255,255,.08);
}
#footer-bar a{
  color:rgba(255,255,255,.55)!important;
}
#footer-bar a.active-nav,
#footer-bar a:hover{
  color:var(--nav-gold)!important;
}
#footer-bar a strong{
  border-color:#0A1B36!important;
}
/* Footer: profile PNG → white (same weight as muted FA icons), full white on hover/active */
#footer-bar a .footer-bar__icon-img{
  width:22px!important;
  height:22px!important;
  object-fit:contain;
  display:block;
  margin:0 auto;
  opacity:.55;
  flex-shrink:0;
  filter:brightness(0) invert(1);
  transition:opacity .2s, transform .2s;
}
#footer-bar a:hover .footer-bar__icon-img,
#footer-bar a.active-nav .footer-bar__icon-img{
  opacity:1;
  transform:translateY(-2px);
  filter: invert(76%) sepia(61%) saturate(415%) hue-rotate(352deg) brightness(87%) contrast(82%);
}

/* Home page hero (index) — below navbar */
.home-hero{
  padding:10px 15px 6px;
  max-width:1140px;
  margin:0 auto;
}
.home-hero__card{
  position:relative;
  border-radius:22px;
  overflow:hidden;
  min-height:240px;
  background-color:#000;
  display:flex;
  flex-direction:column;
  justify-content:flex-end;
  padding:20px 16px 18px;
  box-shadow:0 6px 16px rgba(0,0,0,.28);
  contain:layout;
}
.home-hero__bg{
  position:absolute;
  inset:0;
  width:100%;
  height:100%;
  object-fit:cover;
  object-position:center top;
  pointer-events:none;
  z-index:0;
}
.home-hero__card::before{
  content:"";
  position:absolute;
  inset:0;
  background:linear-gradient(180deg,transparent 40%,rgba(0,0,0,.65) 100%);
  pointer-events:none;
  z-index:1;
}
.home-hero__body{
  position:relative;
  z-index:2;
  text-align:center;
}
.home-hero__title{
  margin:0 0 6px;
  font-size:1.35rem;
  font-weight:800;
  color:#fff;
  letter-spacing:.02em;
  line-height:1.25;
}
.home-hero__sub{
  margin:0 0 16px;
  font-size:.95rem;
  font-weight:600;
  color:var(--nav-gold);
  letter-spacing:.02em;
}
.home-hero__actions{
  display:flex;
  gap:10px;
  justify-content:stretch;
}
.home-hero__actions a{
  flex:1;
  text-align:center;
  padding:12px 10px;
  border-radius:14px;
  font-weight:800;
  font-size:.9rem;
  text-decoration:none!important;
  transition:transform .12s ease;
}
.home-hero__actions a:active{transform:scale(.98)}
.home-hero__btn-starline{
  background:#0A1B36!important;
  color:var(--nav-gold)!important;
  border:1px solid var(--nav-gold)!important;
  box-shadow:0 2px 0 rgba(0,0,0,.2);
}
.home-hero__btn-deposit{
  background:linear-gradient(180deg,#f0d27a,#caa64a)!important;
  color:#fff!important;
  border:none!important;
  box-shadow:0 3px 10px rgba(202,166,74,.25);
  text-shadow:0 1px 0 rgba(0,0,0,.12);
}

/* Professional Scrolling Notice Bar */
.notice-bar-new {
    display: flex !important;
    align-items: center;
    background: #0A1B36 !important;
    border: 1px solid rgba(232, 184, 74, 0.2) !important;
    border-radius: 12px !important;
    margin: 10px 15px 15px !important;
    overflow: hidden;
    height: 42px !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25) !important;
}
.notice-bar-new__label {
    background: linear-gradient(180deg, #f0d27a 0%, #caa64a 100%) !important;
    color: #1a1200 !important;
    padding: 0 14px !important;
    font-weight: 800 !important;
    font-size: 11px !important;
    height: 100% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    letter-spacing: 1px !important;
    white-space: nowrap !important;
    position: relative;
    z-index: 2;
}
.notice-bar-new__content {
    flex: 1 !important;
    overflow: hidden !important;
    position: relative !important;
    display: block !important;
}
.notice-bar-new__track {
    display: inline-block !important;
    white-space: nowrap !important;
    animation: marquee_professional 20s linear infinite !important;
    color: #caa64a !important;
    font-weight: 600 !important;
    font-size: 13px !important;
    padding-left: 100% !important;
    will-change: transform;
}
@keyframes marquee_professional {
    0% { transform: translateX(0); }
    100% { transform: translateX(-100%); }
}

/* history.php — body.page-history */
body.page-history .history-page{
  max-width:560px;
  margin:0 auto;
  padding:12px 14px 24px;
}
body.page-history .history-page__head{
  text-align:center;
  margin-bottom:20px;
}
body.page-history .history-page__title{
  color:#fff;
  font-weight:700;
  font-size:1.35rem;
  letter-spacing:.04em;
  margin:0 0 6px;
}
body.page-history .history-page__sub{
  color:rgba(255,255,255,.5);
  font-size:13px;
  margin:0;
}
body.page-history .history-menu{
  list-style:none;
  margin:0;
  padding:0;
}
body.page-history .history-menu__link{
  display:block;
  text-decoration:none!important;
  margin-bottom:12px;
}
body.page-history .history-menu__link:last-child .history-menu__card{
  margin-bottom:0;
}
body.page-history .history-menu__card{
  display:flex;
  align-items:center;
  gap:14px;
  padding:14px 16px;
  background:rgba(10,27,54,.85);
  border:1px solid rgba(255,255,255,.1);
  border-radius:14px;
  box-shadow:0 4px 14px rgba(0,0,0,.25);
  transition:border-color .2s, background .2s, transform .15s;
}
body.page-history .history-menu__link:hover .history-menu__card,
body.page-history .history-menu__link:focus .history-menu__card{
  border-color:rgba(232,184,74,.45);
  background:rgba(15,35,70,.95);
}
body.page-history .history-menu__link:active .history-menu__card{
  transform:scale(0.99);
}
body.page-history .history-menu__ico{
  flex:0 0 48px;
  width:48px;
  height:48px;
  border-radius:50%;
  background:rgba(147,197,253,.18);
  color:#93c5fd;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:20px;
  overflow:hidden;
}
body.page-history .history-menu__ico img{
  width:26px;
  height:26px;
  object-fit:contain;
  display:block;
}
body.page-history .history-menu__text{
  flex:1 1 auto;
  min-width:0;
  color:rgba(255,255,255,.92);
  font-weight:600;
  font-size:15px;
  letter-spacing:.02em;
}
body.page-history .history-menu__chev{
  flex:0 0 auto;
  color:rgba(255,255,255,.35);
  font-size:14px;
}

/* my-profile.php — body.page-profile (dark + terracotta tiles, bank.png) */
body.page-profile .profile-page{
  max-width:520px;
  margin:0 auto;
  padding:8px 14px 28px;
}
body.page-profile .profile-header{
  display:flex;
  align-items:center;
  gap:16px;
  padding:18px 4px 8px;
}
body.page-profile .profile-header__avatar{
  flex:0 0 64px;
  width:64px;
  height:64px;
  border-radius:50%;
  border:3px solid #38bdf8;
  overflow:hidden;
  background:#0f172a;
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 0 0 1px rgba(56,189,248,.25);
}
body.page-profile .profile-header__avatar img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}
body.page-profile .profile-header__avatar .fa{
  font-size:28px;
  color:rgba(255,255,255,.85);
}
body.page-profile .profile-header__name{
  color:#fff;
  font-weight:700;
  font-size:1.25rem;
  letter-spacing:.02em;
  line-height:1.2;
}
body.page-profile .profile-tiles{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:12px;
  padding:16px 0 0;
}
@media (max-width:340px){
  body.page-profile .profile-tiles{gap:10px;}
}
body.page-profile .profile-tile{
  display:block;
  text-decoration:none!important;
  border:1px solid #8b5a2b;
  border-radius:16px;
  padding:20px 10px 16px;
  text-align:center;
  background:rgba(10,27,54,.55);
  box-shadow:0 4px 14px rgba(0,0,0,.28);
  transition:border-color .2s, background .2s, transform .12s;
}
body.page-profile .profile-tile:hover,
body.page-profile .profile-tile:focus{
  border-color:#c2783a;
  background:rgba(15,35,70,.75);
}
body.page-profile .profile-tile:active{
  transform:scale(.98);
}
body.page-profile .profile-tile__visual{
  width:88px;
  height:88px;
  margin:0 auto 14px;
  border-radius:50%;
  background:#8b5a2b;
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.12);
}
body.page-profile .profile-tile__inner{
  width:54px;
  height:54px;
  border-radius:50%;
  background:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
}
body.page-profile .profile-tile__inner img{
  width:30px;
  height:30px;
  object-fit:contain;
  display:block;
}
body.page-profile .profile-tile__inner .fa{
  font-size:22px;
  color:#8b5a2b;
}
body.page-profile .profile-tile__label{
  display:block;
  color:#fff;
  font-weight:600;
  font-size:14px;
  letter-spacing:.02em;
  line-height:1.25;
}

/* update-bank-details.php — body.page-bank-details (dark + gold fields) */
body.page-bank-details .bank-details-page{
  max-width:520px;
  margin:0 auto;
  padding-bottom:24px;
}
body.page-bank-details .bank-details-card{
  background:rgba(5,10,24,.96);
  border:1px solid rgba(212,136,6,.35);
  border-radius:18px;
  padding:20px 16px 18px;
  box-shadow:0 8px 28px rgba(0,0,0,.4);
}
body.page-bank-details .bank-details-page__head{
  display:flex;
  align-items:center;
  gap:14px;
  margin-bottom:22px;
}
body.page-bank-details .bank-details-page__head-icon{
  flex:0 0 52px;
  width:52px;
  height:52px;
  border-radius:50%;
  background:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  box-shadow:0 2px 10px rgba(0,0,0,.2);
}
body.page-bank-details .bank-details-page__head-icon img{
  width:28px;
  height:28px;
  object-fit:contain;
  display:block;
}
body.page-bank-details .bank-details-page__head-text h3{
  margin:0 0 4px;
  color:#fff!important;
  font-weight:700;
  font-size:1.2rem;
  letter-spacing:.02em;
}
body.page-bank-details .bank-details-page__head-text span,
body.page-bank-details .bank-details-page__head-text span.text-muted{
  font-size:13px;
  color:#fff!important;
  opacity:.92;
}
body.page-bank-details .bank-details-card label{
  display:block;
  font-size:11px!important;
  font-weight:600!important;
  color:#fff!important;
  margin-bottom:6px;
  letter-spacing:.03em;
}
body.page-bank-details .bank-details-card label.text-secondary{
  color:#fff!important;
}
body.page-bank-details .bank-details-card .form-group{
  margin-bottom:16px!important;
}
body.page-bank-details .bank-details-card .input-group{
  border:1px solid #d48806;
  border-radius:10px;
  overflow:hidden;
  background:#050a14;
}
body.page-bank-details .bank-details-card .input-group-text{
  background:#050a14!important;
  border:0!important;
  color:#d48806!important;
  padding:12px 14px!important;
  border-radius:0!important;
}
body.page-bank-details .bank-details-card .input-group-text i{
  color:#d48806!important;
}
body.page-bank-details .bank-details-card .form-control{
  background:#050a14!important;
  border:0!important;
  color:#fff!important;
  padding:12px 14px!important;
  min-height:48px;
  font-size:15px;
  border-radius:0!important;
  box-shadow:none!important;
}
body.page-bank-details .bank-details-card .form-control:focus{
  box-shadow:none!important;
  background:#070f1c!important;
}
body.page-bank-details .bank-details-card .form-control::placeholder{
  color:rgba(255,255,255,.45)!important;
}
body.page-bank-details .bank-details-card button[name="update_bank_details"]{
  background:linear-gradient(180deg,#e8a024,#c97812)!important;
  border:none!important;
  color:#fff!important;
  border-radius:12px!important;
  font-weight:700!important;
  letter-spacing:.04em;
  box-shadow:0 4px 14px rgba(212,136,6,.35);
}
body.page-bank-details .bank-details-card button[name="update_bank_details"]:hover{
  filter:brightness(1.05);
  color:#fff!important;
}
body.page-bank-details .bank-details-card button.btn-secondary:disabled{
  background:rgba(255,255,255,.08)!important;
  border:1px solid rgba(255,255,255,.15)!important;
  color:rgba(255,255,255,.45)!important;
  border-radius:12px!important;
}
body.page-bank-details .bank-details-card .alert-success{
  background:rgba(34,197,94,.12)!important;
  border:1px solid rgba(34,197,94,.35)!important;
  color:#86efac!important;
  border-radius:12px!important;
}
body.page-bank-details .bank-details-card .text-muted{
  color:rgba(255,255,255,.45)!important;
}
body.page-bank-details .bank-details-card .btn-outline{
  background:#fff!important;
  border:2px solid #d48806!important;
  color:#d48806!important;
  border-radius:12px!important;
}
body.page-bank-details .bank-details-card .btn-outline:hover{
  background:rgba(212,136,6,.12)!important;
  color:#f0a030!important;
}
body.page-bank-details .bank-details-card .btn-outline .fa-whatsapp{
  color:#25d366!important;
}

/* change-password.php — same look as bank form; scoped with :has(#old_password) (no PHP change) */
body:has(#old_password) #content .container.py-4{
  max-width:520px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding-bottom:24px!important;
}
body:has(#old_password) #content .card.shadow-lg{
  background:rgba(5,10,24,.96)!important;
  border:1px solid rgba(212,136,6,.35)!important;
  border-radius:18px!important;
  padding:20px 16px 18px!important;
  box-shadow:0 8px 28px rgba(0,0,0,.4)!important;
}
body:has(#old_password) #content .card .text-center h3{
  color:#fff!important;
  font-weight:700!important;
  font-size:1.2rem!important;
  letter-spacing:.02em!important;
  margin-bottom:6px!important;
}
body:has(#old_password) #content .card .text-center span.text-muted,
body:has(#old_password) #content .card .text-center span{
  font-size:13px!important;
  color:#fff!important;
  opacity:.92!important;
}
body:has(#old_password) #content .card label{
  display:block!important;
  font-size:11px!important;
  font-weight:600!important;
  color:#fff!important;
  margin-bottom:6px!important;
  letter-spacing:.03em!important;
}
body:has(#old_password) #content .card label.text-secondary{
  color:#fff!important;
}
body:has(#old_password) #content .card .form-group{
  margin-bottom:16px!important;
}
body:has(#old_password) #content .card .input-group{
  border:1px solid #d48806!important;
  border-radius:10px!important;
  overflow:hidden!important;
  background:#050a14!important;
}
body:has(#old_password) #content .card .input-group-text{
  background:#050a14!important;
  border:0!important;
  color:#d48806!important;
  padding:12px 14px!important;
  border-radius:0!important;
}
body:has(#old_password) #content .card .input-group-text i{
  color:#d48806!important;
}
body:has(#old_password) #content .card .form-control{
  background:#050a14!important;
  border:0!important;
  color:#fff!important;
  padding:12px 14px!important;
  min-height:48px!important;
  font-size:15px!important;
  border-radius:0!important;
  box-shadow:none!important;
}
body:has(#old_password) #content .card .form-control:focus{
  box-shadow:none!important;
  background:#070f1c!important;
  color:#fff!important;
}
body:has(#old_password) #content .card .form-control::placeholder{
  color:rgba(255,255,255,.45)!important;
}
body:has(#old_password) #content .card button[name="change_password"]{
  background:linear-gradient(180deg,#e8a024,#c97812)!important;
  border:none!important;
  color:#fff!important;
  border-radius:12px!important;
  font-weight:700!important;
  letter-spacing:.04em!important;
  box-shadow:0 4px 14px rgba(212,136,6,.35)!important;
  margin-top:4px!important;
}
body:has(#old_password) #content .card button[name="change_password"]:hover{
  filter:brightness(1.05)!important;
  color:#fff!important;
}

/* Sidebar (#sidebar) — dark navy + gold active row, icon circles, chevrons (sidebar.php markup unchanged) */
#sidebar{
  background:#000b1a!important;
  box-shadow:6px 0 24px rgba(0,0,0,.45)!important;
  overflow-y:auto!important;
  overflow-y:none!important;
}
#sidebar::-webkit-scrollbar-thumb{
  background:rgba(212,136,6,.35)!important;
}
#sidebar .sidebar-header{
  position:relative!important;
  padding:20px 20px 10px!important;
  text-align:center!important;
  border-bottom-right-radius:0!important;
  background-color:#000b1a!important;
  background-image:
    linear-gradient(118deg, rgba(212,136,6,.14) 0%, transparent 42%),
    linear-gradient(305deg, rgba(184,110,40,.1) 18%, transparent 48%),
    linear-gradient(28deg, rgba(255,210,140,.08) 0%, transparent 38%)!important;
  color:#fff!important;
  border-bottom:1px solid rgba(26,42,58,.9);
}
#sidebar .sidebar-header .user-avatar{
  width:50px!important;
  height:50px!important;
  margin:0 auto 8px!important;
  background:rgba(10,29,55,.85)!important;
  border:2px solid rgba(255,255,255,.35)!important;
  box-shadow:0 4px 14px rgba(0,0,0,.35);
}
#sidebar .sidebar-header .user-avatar .fa{
  color:#fff!important;
  font-size:26px!important;
}
#sidebar .Uname{
  color:#fff!important;
  font-size:18px!important;
  font-weight:700!important;
}
#sidebar .Umobile{
  color:rgba(255,255,255,.65)!important;
  font-size:13px!important;
}
#dismiss.sidebar-dismiss,
#dismiss{
  position:absolute!important;
  top:12px!important;
  right:12px!important;
  width:40px!important;
  height:40px!important;
  margin:0!important;
  padding:0!important;
  line-height:1!important;
  border-radius:10px!important;
  cursor:pointer!important;
  z-index:5!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  background:rgba(10,29,55,.85)!important;
  border:1px solid rgba(212,136,6,.4)!important;
  color:#fff!important;
  box-shadow:0 2px 10px rgba(0,0,0,.25);
  -webkit-appearance:none!important;
  appearance:none!important;
}
#dismiss.sidebar-dismiss .fa,
#dismiss .fa{
  font-size:18px!important;
  line-height:1!important;
  color:#f0b24a!important;
}
#dismiss.sidebar-dismiss:hover,
#dismiss:hover{
  background:rgba(212,136,6,.22)!important;
  border-color:rgba(232,184,74,.65)!important;
  color:#fff!important;
}
#dismiss.sidebar-dismiss:hover .fa,
#dismiss:hover .fa{
  color:#fff!important;
}
#sidebar ul.components{
  padding:6px 0 0!important;
  margin-bottom:0!important;
}
#sidebar ul.components > li{
  border-bottom:1px solid #1a2a3a;
}
#sidebar ul.components > li:last-child{
  border-bottom:none;
}
#sidebar ul.components li > a{
  margin:0!important;
  border-radius:0!important;
  padding:8px 16px!important;
  color:#fff!important;
  background:transparent!important;
  font-weight:600!important;
  gap:14px!important;
  border-bottom:none!important;
}
#sidebar ul.components li > a > i,
#sidebar ul.components li > a > span.sidebar-nav-icon-wrap{
  width:36px!important;
  height:36px!important;
  min-width:36px!important;
  border-radius:50%!important;
  background:#0a1d37!important;
  color:#fff!important;
  font-size:18px!important;
  display:inline-flex!important;
  align-items:center!important;
  justify-content:center!important;
  flex-shrink:0!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.06);
}
#sidebar ul.components li > a > span.sidebar-nav-icon-wrap img{
  width:21px!important;
  height:21px!important;
  object-fit:contain!important;
  display:block!important;
  flex-shrink:0!important;
  filter:brightness(0) invert(1) brightness(1.2)!important;
  opacity:0.98!important;
}
#sidebar ul.components li > a > span:not(.sidebar-nav-icon-wrap){
  flex:1 1 auto!important;
  color:#fff!important;
  text-align:left!important;
}
#sidebar ul.components li > a::after{
  content:"\f105";
  font-family:FontAwesome;
  font-size:16px;
  color:rgba(255,255,255,.55);
  margin-left:8px;
  flex-shrink:0;
}
#sidebar ul.components li > a:hover,
#sidebar ul.components li.active > a{
  background:linear-gradient(90deg, #b35a00 0%, #d48806 38%, #e8a030 72%, #f0b24a 100%)!important;
  color:#fff!important;
}
#sidebar ul.components li > a:hover > i,
#sidebar ul.components li.active > a > i,
#sidebar ul.components li > a:hover > span.sidebar-nav-icon-wrap,
#sidebar ul.components li.active > a > span.sidebar-nav-icon-wrap{
  background:rgba(0,0,0,.22)!important;
  color:#fff!important;
}
#sidebar ul.components li > a:hover::after,
#sidebar ul.components li.active > a::after{
  color:#fff!important;
}
#sidebar ul.CTAs{
  padding:6px 14px 20px!important;
  border-top:1px solid #1a2a3a!important;
  margin-top:0!important;
}
#sidebar ul.CTAs li{
  margin-bottom:10px!important;
}
#sidebar ul.CTAs a.download{
  background:linear-gradient(180deg,#e8a024,#c97812)!important;
  color:#fff!important;
  border-radius:12px!important;
  font-weight:700!important;
  padding:12px 16px!important;
  border:none!important;
}
#sidebar ul.CTAs a.download:hover{
  filter:brightness(1.06);
  color:#fff!important;
}
#sidebar ul.CTAs li.active > a.download{
  box-shadow:0 0 0 2px rgba(255,255,255,.35), 0 6px 20px rgba(212,136,6,.45)!important;
  filter:brightness(1.08);
}
#sidebar ul.CTAs a.article{
  background:transparent!important;
  color:#fff!important;
  border:1px solid rgba(255,255,255,.28)!important;
  border-radius:12px!important;
  font-weight:600!important;
  padding:8px 16px!important;
}
#sidebar ul.CTAs a.article:hover{
  background:rgba(255,255,255,.06)!important;
  color:#fff!important;
}

/* withdraw.php — scoped with body.page-withdraw */
body.page-withdraw {
  overflow-y: auto!important;
}
body.page-withdraw #content > .container {
  max-width: 440px!important;
  margin-left: auto!important;
  margin-right: auto!important;
  padding-bottom: 24px!important;
}
body.page-withdraw .card {
  background: rgba(5,10,24,.96)!important;
  border: 1px solid rgba(212,136,6,.35)!important;
  border-radius: 18px!important;
  box-shadow: 0 8px 28px rgba(0,0,0,.4)!important;
}
body.page-withdraw h3.gdash3,
body.page-withdraw .text-secondary,
body.page-withdraw .text-muted,
body.page-withdraw .withdraw-total-amt {
  color: #fff!important;
}
body.page-withdraw .withdraw-total-summary small {
  color: rgba(255,255,255,.65)!important;
}
body.page-withdraw .withdraw-total-amt {
  font-size: 24px!important;
  font-weight: 700!important;
  color: #d48806!important;
}
body.page-withdraw .withdraw-page-icon {
  width: 64px;
  height: 64px;
  background: #050a14;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  border: 1px solid #d48806;
  font-size: 28px;
  color: #d48806;
}
body.page-withdraw .withdraw-alert-info {
  background: rgba(212,136,6,.12)!important;
  border: 1px solid rgba(212,136,6,.35)!important;
  color: #f0a030!important;
  border-radius: 12px!important;
  width: 75%!important;
  margin-left: auto!important;
  margin-right: auto!important;
}
body.page-withdraw .withdraw-alert-bank {
  background: rgba(220,53,69,.12)!important;
  border: 1px solid rgba(220,53,69,.35)!important;
  color: #ff6b6b!important;
  border-radius: 12px!important;
  width: 75%!important;
  margin-left: auto!important;
  margin-right: auto!important;
}
body.page-withdraw .withdraw-btn-outline {
  width: 75%!important;
  margin: 0 auto 16px!important;
  display: block!important;
}
body.page-withdraw .withdraw-label-amt {
  color:#fff!important;
  font-size:12px!important;
  margin-bottom:8px!important;
}
body.page-withdraw .withdraw-input-amt {
  background: #050a14!important;
  border: 1px solid #d48806!important;
  color: #fff!important;
  padding: 12px 14px!important;
  height: auto!important;
  border-radius: 10px!important;
  font-size: 16px!important;
  box-shadow: none!important;
}
body.page-withdraw .withdraw-input-amt:focus {
  background: #070f1c!important;
  box-shadow: 0 0 0 2px rgba(212,136,6,.25)!important;
}
body.page-withdraw .withdraw-main-form {
}
body.page-withdraw .withdraw-submit-btn {
  background: linear-gradient(180deg,#e8a024,#c97812)!important;
  border: none!important;
  color: #fff!important;
  border-radius: 12px!important;
  font-weight: 700!important;
  letter-spacing: .04em;
  box-shadow: 0 4px 14px rgba(212,136,6,.35);
  font-size: 16px!important;
  width: 75%!important;
  margin-left: auto!important;
  margin-right: auto!important;
  display: block!important;
}
body.page-withdraw .withdraw-submit-btn:hover {
  filter: brightness(1.05);
}
body.page-withdraw .withdraw-support {
  width: 75%!important;
  margin: 0 auto!important;
}
body.page-withdraw .withdraw-support .withdraw-help-text {
  color: rgba(255,255,255,.55)!important;
}
body.page-withdraw .withdraw-btn-wa {
  background: rgba(37,211,102,.12)!important;
  border: 1px solid rgba(37,211,102,.45)!important;
  color: #25d366!important;
  border-radius: 12px!important;
  padding: 12px!important;
}
body.page-withdraw .withdraw-btn-wa:hover {
  background: rgba(37,211,102,.2)!important;
  color: #40f280!important;
}
body.page-withdraw .withdraw-btn-wa i {
  color: #25d366!important;
}
body.page-withdraw .container > .text-center {
  color: #fff;
}
body.page-withdraw .container > .text-center h3 {
  color: #fff;
  font-weight: 700;
}
body.page-withdraw .container > .text-center span {
  color: rgba(255,255,255,.65);
}

/* support.php — body.page-support + Telegram link cards */
body.page-support #content > .container{
  max-width:520px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding-left:14px!important;
  padding-right:14px!important;
  padding-bottom:24px!important;
}
body.page-support .support-page-head.card{
  max-width:460px!important;
  width:100%!important;
  margin-left:auto!important;
  margin-right:auto!important;
  background:rgba(10,27,54,.88)!important;
  border:1px solid rgba(212,136,6,.38)!important;
  border-radius:14px!important;
  box-shadow:0 4px 16px rgba(0,0,0,.32)!important;
}
body.page-support .support-page-head__icon{
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
}
body.page-support .support-page-head__img{
  width:72px!important;
  height:auto!important;
  max-width:100%!important;
  display:block!important;
  object-fit:contain!important;
  filter:brightness(0) saturate(100%) invert(73%) sepia(85%) saturate(520%) hue-rotate(358deg) brightness(1.06) contrast(1.05)!important;
}
body.page-support .support-page-head__title{
  color:var(--nav-gold,#e8b84a)!important;
  font-size:1.25rem!important;
  margin:0!important;
}
body.page-support .support-page-head__sub{
  display:block!important;
  color:#fff!important;
  font-size:13px!important;
  line-height:1.45!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .card .card-body.d-flex{
  display:flex!important;
  flex-direction:row!important;
  flex-wrap:nowrap!important;
  align-items:center!important;
  justify-content:flex-start!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .card .card-body.d-flex .flex-grow-1{
  flex:1 1 auto!important;
  min-width:0!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .card{
  background:rgba(10,27,54,.88)!important;
  border:1px solid rgba(212,136,6,.38)!important;
  border-radius:14px!important;
  box-shadow:0 4px 16px rgba(0,0,0,.32)!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .card:hover{
  border-color:rgba(232,184,74,.55)!important;
  background:rgba(15,35,70,.92)!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .card .card-body.d-flex > div:first-child{
  width:48px!important;
  height:48px!important;
  min-width:48px!important;
  border-radius:50%!important;
  background:#0a1d37!important;
  margin-right:14px!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  font-size:22px!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.06);
}
body:has(a[href*="t.me/Ritikdp99"]) #content .card .fa-whatsapp{
  color:#25d366!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .card .fa-telegram{
  color:#38bdf8!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .card h6.text-dark{
  color:#fff!important;
  font-weight:600!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .card small.text-muted{
  color:rgba(255,255,255,.55)!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .card .card-body.d-flex > .fa-chevron-right{
  margin-left:auto!important;
  flex-shrink:0!important;
  align-self:center!important;
  display:inline-flex!important;
  align-items:center!important;
  justify-content:center!important;
  color:rgba(255,255,255,.55)!important;
  font-size:14px!important;
  line-height:1!important;
  padding-left:6px!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .badge.badge-light{
  background:rgba(10,27,54,.9)!important;
  border:1px solid rgba(212,136,6,.35)!important;
  color:rgba(255,255,255,.88)!important;
}
body:has(a[href*="t.me/Ritikdp99"]) #content .badge .fa-shield{
  color:#4ade80!important;
}

/* notifications.php — meta description unique (no PHP change) */
html:has(meta[name="description"][content*="Latest Notification list"]) #content > .container{
  max-width:520px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding-left:14px!important;
  padding-right:14px!important;
  padding-bottom:24px!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .text-center.tb-10 > div:first-of-type{
  width:64px!important;
  height:64px!important;
  margin:0 auto 14px!important;
  background:rgba(10,29,55,.9)!important;
  border:2px solid rgba(212,136,6,.45)!important;
  border-radius:50%!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  box-shadow:0 4px 14px rgba(0,0,0,.3);
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .text-center.tb-10 .fa-bell{
  color:#d48806!important;
  font-size:26px!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .text-center.tb-10 h3{
  color:var(--nav-gold,#e8b84a)!important;
  font-weight:700!important;
  font-size:1.25rem!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .text-center.tb-10 span.text-muted{
  color:rgba(255,255,255,.75)!important;
  font-size:13px!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .card{
  background:rgba(10,27,54,.9)!important;
  border:1px solid rgba(212,136,6,.38)!important;
  border-radius:14px!important;
  box-shadow:0 4px 16px rgba(0,0,0,.32)!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .card:hover{
  border-color:rgba(232,184,74,.55)!important;
  background:rgba(15,35,70,.95)!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .notification-card-row,
html:has(meta[name="description"][content*="Latest Notification list"]) #content .notification-card__row{
  display:flex!important;
  flex-direction:row!important;
  flex-wrap:nowrap!important;
  align-items:flex-start!important;
  width:100%!important;
  gap:14px!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .notification-card__main{
  flex:1 1 auto!important;
  min-width:0!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .notification-card__icon{
  width:44px!important;
  min-width:44px!important;
  max-width:44px!important;
  flex-shrink:0!important;
  display:flex!important;
  align-items:flex-start!important;
  justify-content:center!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .notification-card__icon-img{
  width:44px!important;
  height:44px!important;
  object-fit:contain!important;
  display:block!important;
  border-radius:10px!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .notification-card__headline{
  display:flex!important;
  justify-content:space-between!important;
  align-items:flex-start!important;
  gap:10px!important;
  width:100%!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .notification-card__title{
  color:#fff!important;
  font-weight:600!important;
  line-height:1.35!important;
  flex:1 1 auto!important;
  min-width:0!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .notification-card__desc{
  color:rgba(255,255,255,.62)!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .notification-card__date{
  color:rgba(232,184,74,.9)!important;
  text-align:right!important;
  line-height:1.3!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .notification-card__date .fa-clock-o{
  margin-right:3px!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .tbmar-40{
  padding:28px 16px!important;
  border-radius:14px!important;
  background:rgba(10,27,54,.75)!important;
  border:1px dashed rgba(212,136,6,.35)!important;
  color:rgba(255,255,255,.75)!important;
}
html:has(meta[name="description"][content*="Latest Notification list"]) #content .tbmar-40 p{
  margin:0!important;
  color:rgba(255,255,255,.7)!important;
}

/* top-winner-list.php — body.page-top-winner-list */
body.page-top-winner-list #content{
  background:#050b1a!important;
  min-height:100vh!important;
  overflow-x:hidden!important;
}
body.page-top-winner-list #content > .container{
  max-width:520px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding:12px 14px 88px!important;
  box-sizing:border-box!important;
}
body.page-top-winner-list .top-winners-hero{
  margin-top:20px!important;
  padding:8px 4px 22px!important;
  margin-bottom:8px!important;
  border-bottom:1px solid rgba(232,184,74,.22)!important;
}
body.page-top-winner-list .top-winners-hero__icon-wrap{
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  margin:0 auto 14px!important;
}
body.page-top-winner-list .top-winners-hero__icon{
  width:80px!important;
  height:auto!important;
  max-width:100%!important;
  object-fit:contain!important;
  display:block!important;
  filter:drop-shadow(0 4px 14px rgba(250,204,21,.35)) brightness(1.08) saturate(1.15)!important;
}
body.page-top-winner-list .top-winners-page .fa{
  color:#facc15!important;
}
body.page-top-winner-list .top-winners-hero__title{
  color:#e8952a!important;
  font-weight:800!important;
  font-size:1.35rem!important;
  letter-spacing:.02em!important;
  margin:0 0 8px!important;
  line-height:1.25!important;
}
body.page-top-winner-list .top-winners-hero__sub{
  margin:0!important;
  color:rgba(255,255,255,.78)!important;
  font-size:13px!important;
  line-height:1.45!important;
}
body.page-top-winner-list .top-winners-list{
  padding-top:6px!important;
}
body.page-top-winner-list .top-winner-card{
  background:rgba(10,27,54,.92)!important;
  border:1px solid rgba(212,136,6,.38)!important;
  border-radius:16px!important;
  box-shadow:0 8px 24px rgba(0,0,0,.35)!important;
  overflow:hidden!important;
}
body.page-top-winner-list .top-winner-card:hover{
  border-color:rgba(232,184,74,.55)!important;
  background:rgba(15,35,70,.96)!important;
}
body.page-top-winner-list .top-winner-card__inner{
  padding:16px 14px!important;
}
body.page-top-winner-list .top-winner-card__date-row{
  text-align:left!important;
  margin-bottom:10px!important;
}
body.page-top-winner-list .top-winner-card__time{
  color:rgba(255,255,255,.72)!important;
  font-size:12px!important;
  display:inline-block!important;
}
body.page-top-winner-list .top-winner-card__name{
  color:#fff!important;
  font-size:16px!important;
  font-weight:700!important;
  text-align:center!important;
  width:100%!important;
  margin:0 0 12px!important;
  line-height:1.35!important;
}
body.page-top-winner-list .top-winner-card__meta-row{
  text-align:center!important;
  margin-bottom:4px!important;
}
body.page-top-winner-list .top-winner-card__meta{
  color:rgba(255,255,255,.62)!important;
  font-size:12px!important;
  line-height:1.5!important;
}
body.page-top-winner-list .top-winner-card__digit{
  background:rgba(0,0,0,.35)!important;
  color:rgba(255,255,255,.9)!important;
  border:1px solid rgba(212,136,6,.4)!important;
}
body.page-top-winner-list .top-winner-card__prize-block{
  text-align:center!important;
  margin-top:14px!important;
  padding-top:12px!important;
  border-top:1px solid rgba(232,184,74,.15)!important;
}
body.page-top-winner-list .top-winner-card__prize-label{
  color:rgba(255,255,255,.55)!important;
  font-size:11px!important;
  margin-bottom:4px!important;
}
body.page-top-winner-list .top-winner-card__prize-amt{
  color:#f0b24a!important;
  font-size:18px!important;
  font-weight:800!important;
}

/* top-winner-list-starline.php — unique meta (no PHP change) */
html:has(meta[name="description"][content*="starline top winner list"]) #content > .container{
  max-width:520px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding-left:14px!important;
  padding-right:14px!important;
  padding-top:20px!important;
  padding-bottom:24px!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .tb-10{
  text-align:center!important;
  margin-top:6px!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .tb-10 h1.gdash3{
  color:#fff!important;
  font-weight:700!important;
  font-size:1.35rem!important;
  margin-bottom:8px!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .tb-10 > span{
  display:block!important;
  color:rgba(255,255,255,.7)!important;
  font-size:13px!important;
  margin-bottom:18px!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .card{
  background:rgba(10,27,54,.9)!important;
  border:1px solid rgba(212,136,6,.38)!important;
  border-radius:14px!important;
  box-shadow:0 4px 16px rgba(0,0,0,.32)!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .card:hover{
  border-color:rgba(232,184,74,.55)!important;
  background:rgba(15,35,70,.95)!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .card .card-body.d-flex > div:first-child{
  width:50px!important;
  height:50px!important;
  min-width:50px!important;
  border-radius:50%!important;
  background:#0a1d37!important;
  background-image:none!important;
  margin-right:14px!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.06), 0 2px 10px rgba(0,0,0,.25)!important;
  border:1px solid rgba(212,136,6,.35)!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .card .fa-trophy{
  color:#f0b24a!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .card h6.text-dark,
html:has(meta[name="description"][content*="starline top winner list"]) #content .card h6{
  color:#fff!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .card small.text-muted{
  color:rgba(255,255,255,.58)!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .card .fa-star{
  color:#f0b24a!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .card .badge.badge-light{
  background:rgba(0,0,0,.35)!important;
  color:rgba(255,255,255,.9)!important;
  border:1px solid rgba(212,136,6,.4)!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .card .text-right span.text-muted{
  color:rgba(255,255,255,.5)!important;
}
html:has(meta[name="description"][content*="starline top winner list"]) #content .card .text-right h6{
  color:#f0b24a!important;
}

/* game-rates.php — unique meta (no PHP change) */
html:has(meta[name="description"][content*="winning streak today"]) #content{
  background:#000814!important;
  min-height:100vh!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content > .container{
  max-width:520px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding:18px 14px 32px!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10{
  text-align:center!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10 h1.gdash3{
  color:#fff!important;
  font-weight:700!important;
  font-size:1.35rem!important;
  margin-bottom:6px!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10 h1 .fa-line-chart{
  color:#f0b24a!important;
  margin-right:8px!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10 > span.text-muted{
  color:rgba(255,255,255,.65)!important;
  font-size:13px!important;
  display:block!important;
  margin-bottom:4px!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10 .card{
  background:#041226!important;
  border:1px solid #0a1f3d!important;
  border-radius:12px!important;
  padding:16px 16px 12px!important;
  margin-top:18px!important;
  text-align:left!important;
  box-shadow:0 4px 18px rgba(0,0,0,.35)!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10 .card h2{
  color:#f39c12!important;
  font-size:1.05rem!important;
  font-weight:700!important;
  margin-bottom:12px!important;
  border-bottom:none!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10 .card h2 .fa{
  color:#f39c12!important;
  margin-right:8px!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10 .card .d-flex{
  display:flex!important;
  justify-content:space-between!important;
  align-items:center!important;
  gap:12px!important;
  padding:10px 0!important;
  border-bottom:1px solid rgba(10,31,61,.95)!important;
  margin:0!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10 .card .d-flex:last-of-type{
  border-bottom:none!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10 .card .d-flex .text-dark{
  color:#fff!important;
  font-weight:600!important;
  font-size:14px!important;
}
html:has(meta[name="description"][content*="winning streak today"]) #content .tb-10 .card .d-flex .text-success{
  color:#fff!important;
  font-weight:700!important;
  font-size:14px!important;
  white-space:nowrap!important;
}

/* download-app.php — match app shell: navy #0A1B36 + gold #e8b84a (head.php nav), dark body */
html:has(meta[name="description"][content*="stay connected with us"]) #content{
  background:#000!important;
  min-height:100vh!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) #content > .container.download-wrapper{
  max-width:440px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding:12px 14px 88px!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-header{
  text-align:center!important;
  margin-bottom:16px!important;
  padding-top:4px!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-header h3{
  color:var(--nav-gold,#e8b84a)!important;
  font-size:1.4rem!important;
  font-weight:800!important;
  margin:0 0 8px!important;
  letter-spacing:-.02em!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-header span.text-muted{
  color:rgba(255,255,255,.55)!important;
  font-size:13px!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-card.card{
  border:1px solid rgba(232,184,74,.32)!important;
  border-radius:20px!important;
  overflow:hidden!important;
  background:rgba(14,18,28,.96)!important;
  box-shadow:0 16px 48px rgba(0,0,0,.55),0 0 0 1px rgba(10,27,54,.6) inset!important;
  padding:0!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-hero{
  display:flex!important;
  flex-direction:row!important;
  align-items:flex-start!important;
  gap:12px!important;
  padding:16px 14px 14px!important;
  background:linear-gradient(165deg,rgba(240,98,146,.18) 0%,#0A1B36 42%,#0d1528 100%)!important;
  color:#fff!important;
  border-bottom:1px solid rgba(232,184,74,.12)!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-hero__left{
  flex:0 0 38%!important;
  max-width:150px!important;
  min-width:0!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-hero__left img{
  display:block!important;
  width:100%!important;
  max-width:100%!important;
  height:auto!important;
  margin:0!important;
  border-radius:14px!important;
  box-shadow:0 10px 28px rgba(0,0,0,.45),0 0 0 2px rgba(232,184,74,.35)!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-hero__right{
  flex:1 1 auto!important;
  min-width:0!important;
  text-align:left!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-hero__right h2{
  color:#fff!important;
  font-size:1rem!important;
  font-weight:800!important;
  margin:0 0 10px!important;
  letter-spacing:-.02em!important;
  line-height:1.2!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-actions{
  padding:16px 14px 18px!important;
  background:rgba(8,12,22,.95)!important;
  text-align:center!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-btn.download-btn{
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  gap:10px!important;
  width:100%!important;
  max-width:100%!important;
  margin:0 0 10px!important;
  padding:14px 18px!important;
  border-radius:14px!important;
  background:linear-gradient(180deg,#f0d27a,#caa64a)!important;
  color:#1a1200!important;
  font-weight:800!important;
  font-size:15px!important;
  letter-spacing:.03em!important;
  text-decoration:none!important;
  text-transform:uppercase!important;
  border:1px solid rgba(255,255,255,.2)!important;
  box-shadow:0 8px 22px rgba(202,166,74,.28)!important;
  animation:none!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-btn:hover{
  filter:brightness(1.05)!important;
  text-decoration:none!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-btn .fa-android{
  font-size:1.15em!important;
  color:#1a1200!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .download-tagline{
  font-size:12px!important;
  line-height:1.45!important;
  margin:0!important;
  color:rgba(255,255,255,.6)!important;
  padding:0 6px!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-box{
  padding:0!important;
  margin:0!important;
  background:transparent!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-item{
  display:flex!important;
  align-items:flex-start!important;
  gap:8px!important;
  margin-bottom:12px!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-item:last-child{
  margin-bottom:0!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-icon{
  width:34px!important;
  height:34px!important;
  min-width:34px!important;
  border-radius:9px!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  margin-right:0!important;
  border:1px solid rgba(255,255,255,.06)!important;
  font-size:14px!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-item:nth-child(1) .feature-icon{
  background:rgba(232,184,74,.16)!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-item:nth-child(1) .feature-icon .fa-rocket{
  color:var(--nav-gold,#e8b84a)!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-item:nth-child(2) .feature-icon{
  background:rgba(40,167,69,.12)!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-item:nth-child(2) .feature-icon .fa-shield{
  color:#6fcf97!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-item:nth-child(3) .feature-icon{
  background:rgba(23,162,184,.12)!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-item:nth-child(3) .feature-icon .fa-bell{
  color:#7eb8da!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-text h6{
  color:rgba(255,255,255,.95)!important;
  font-weight:700!important;
  font-size:13px!important;
  margin:0 0 2px!important;
  line-height:1.25!important;
}
html:has(meta[name="description"][content*="stay connected with us"]) .feature-text p.text-muted{
  color:rgba(255,255,255,.52)!important;
  font-size:11px!important;
  margin:0!important;
  line-height:1.35!important;
}

/* game-dashboard.php — body.page-game-dashboard */
body.page-game-dashboard #content{
  background:#000814!important;
  min-height:100vh!important;
}
body.page-game-dashboard #content > .container{
  max-width:520px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding-left:12px!important;
  padding-right:12px!important;
  padding-bottom:88px!important;
}
body.page-game-dashboard .card-full-page{
  background:transparent!important;
  box-shadow:none!important;
  border-radius:0!important;
  padding:12px 0 24px!important;
  margin:8px 0 0!important;
  min-height:auto!important;
}
body.page-game-dashboard .card-full-page .text-center.tb-10 .gdash3{
  color: #e8b84a!important;
  font-weight: 800!important;
  font-size: 1.5rem!important;
  letter-spacing: .02em!important;
  margin-bottom: 12px!important;
  text-shadow: 0 4px 12px rgba(232,184,74,.25)!important;
}
body.page-game-dashboard .card-full-page .game-dashboard-header {
  margin-bottom: 24px!important;
}
body.page-game-dashboard .card-full-page .text-center.tb-10 .bidding-subtitle-wrap {
  display: inline-block!important;
  background: rgba(255,255,255,.08)!important;
  border: 1px solid rgba(255,255,255,.2)!important;
  border-radius: 8px!important;
  padding: 6px 16px!important;
  box-shadow: 0 4px 12px rgba(0,0,0,.3)!important;
}
body.page-game-dashboard .card-full-page .text-center.tb-10 span.bidding-subtitle{
  color: #fff!important;
  font-weight: 600!important;
  font-size: 14px!important;
  letter-spacing: .04em!important;
  text-transform: uppercase!important;
}
body.page-game-dashboard .bidoptions-list.row{
  display:grid!important;
  grid-template-columns:repeat(3,1fr)!important;
  gap:12px!important;
  align-items:stretch!important;
  margin-left:0!important;
  margin-right:0!important;
  margin-bottom:20px!important;
}
body.page-game-dashboard .bidoptions-list.row > .col-4{
  flex:0 0 auto!important;
  max-width:none!important;
  width:100%!important;
  padding-left:0!important;
  padding-right:0!important;
}
body.page-game-dashboard a.bidtypebox{
  display:flex!important;
  flex-direction:column!important;
  align-items:center!important;
  justify-content:flex-start!important;
  min-height:118px!important;
  height:100%!important;
  margin:0!important;
  padding:14px 8px 12px!important;
  background:#0a1224!important;
  border:1px solid rgba(202,166,74,.65)!important;
  border-radius:14px!important;
  box-shadow:0 4px 14px rgba(0,0,0,.35)!important;
  text-decoration:none!important;
}
body.page-game-dashboard a.bidtypebox:hover,
body.page-game-dashboard a.bidtypebox:focus{
  background:#0d1730!important;
  border-color:rgba(232,184,74,.95)!important;
  box-shadow:0 0 0 1px rgba(232,184,74,.25),0 8px 22px rgba(0,0,0,.4)!important;
  transform:translateY(-1px)!important;
}
body.page-game-dashboard a.bidtypebox img{
  width:72px!important;
  height:72px!important;
  min-width:72px!important;
  min-height:72px!important;
  padding:0!important;
  margin:0 0 10px!important;
  object-fit:contain!important;
  background:transparent!important;
  box-shadow:none!important;
  border-radius:0!important;
  box-sizing:content-box!important;
}
body.page-game-dashboard a.bidtypebox p{
  color:#fff!important;
  font-size:12px!important;
  font-weight:600!important;
  line-height:1.25!important;
  margin:0!important;
  text-align:center!important;
}
body.page-game-dashboard .tbmar-40.text-center span{
  color:rgba(255,255,255,.85)!important;
  font-size:13px!important;
}
body.page-game-dashboard .game-status-note {
  background: linear-gradient(135deg, rgba(232,184,74,.18), rgba(212,136,6,.08))!important;
  border: 1px solid rgba(232,184,74,.4)!important;
  border-radius: 12px!important;
  padding: 14px 20px!important;
  color: #f0b24a!important;
  font-weight: 600!important;
  font-size: 15px!important;
  box-shadow: 0 6px 16px rgba(0,0,0,.25)!important;
  display: inline-block!important;
  max-width: 90%!important;
}
body.page-game-dashboard .game-status-note i {
  margin-right: 6px!important;
  font-size: 18px!important;
  vertical-align: middle!important;
}
body.page-game-dashboard .game-status-closed {
  background: linear-gradient(135deg, rgba(220,53,69,.18), rgba(180,30,45,.08))!important;
  border: 1px solid rgba(220,53,69,.4)!important;
  color: #ff6b6b!important;
}

/* add-fund.php — body.page-add-fund */
body.page-add-fund #content{
  background:#020617!important;
  min-height:100vh!important;
  position:relative!important;
  overflow-x:hidden!important;
  max-width:100vw!important;
  box-sizing:border-box!important;
}
body.page-add-fund .wrapper,
body.page-add-fund .add-fund-container{
  max-width:100%!important;
  box-sizing:border-box!important;
}
body.page-add-fund #content::before{
  content:""!important;
  position:fixed!important;
  top:0!important;
  left:0!important;
  right:0!important;
  bottom:0!important;
  background:radial-gradient(circle at 50% 0%, rgba(212,136,6,0.15) 0%, transparent 50%)!important;
  pointer-events:none!important;
  z-index:0!important;
}
body.page-add-fund .add-fund-page{
  position:relative!important;
  z-index:1!important;
  width:100%!important;
  max-width:480px!important;
  margin:0 auto!important;
  padding:16px 15px 100px!important;
  box-sizing:border-box!important;
}
body.page-add-fund .add-fund-glass-card{
  background:rgba(15, 23, 42, 0.8)!important;
  backdrop-filter:blur(12px)!important;
  -webkit-backdrop-filter:blur(12px)!important;
  border:1px solid rgba(212,136,6,0.3)!important;
  border-radius:28px!important;
  padding:32px 24px!important;
  box-shadow:0 25px 50px -12px rgba(0,0,0,0.5)!important;
  width:100%!important;
  max-width:100%!important;
  box-sizing:border-box!important;
}
body.page-add-fund .add-fund-header{
  text-align:center!important;
  margin-bottom:28px!important;
}
body.page-add-fund .add-fund-icon-wrap{
  width:88px!important;
  height:88px!important;
  margin:0 auto 20px!important;
  background:rgba(212, 136, 6, 0.1)!important;
  border-radius:24px!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  border:1px solid rgba(212,136,6,0.2)!important;
  transform:rotate(-5deg)!important;
  box-shadow:0 10px 15px -3px rgba(0,0,0,0.1)!important;
}
body.page-add-fund .add-fund-hero-img{
  width:54px!important;
  height:54px!important;
  object-fit:contain!important;
  transform:rotate(5deg)!important;
}
body.page-add-fund .add-fund-title{
  color:#fff!important;
  font-weight:800!important;
  font-size:1.6rem!important;
  letter-spacing:-0.02em!important;
  margin:0 0 6px!important;
}
body.page-add-fund .add-fund-tagline{
  color:rgba(255,255,255,0.55)!important;
  font-size:14px!important;
  margin:0!important;
}
body.page-add-fund .add-fund-info-bar{
  display:flex!important;
  justify-content:center!important;
  gap:10px!important;
  margin-bottom:32px!important;
  flex-wrap:wrap!important;
}
body.page-add-fund .info-bubble{
  background:rgba(255,255,255,0.05)!important;
  border:1px solid rgba(255,255,255,0.1)!important;
  padding:6px 12px!important;
  border-radius:100px!important;
  font-size:11px!important;
  font-weight:600!important;
  color:rgba(255,255,255,0.8)!important;
  text-transform:uppercase!important;
  letter-spacing:0.04em!important;
}
body.page-add-fund .info-bubble i{
  color:#e8b84a!important;
  margin-right:4px!important;
}
body.page-add-fund .amount-entry-section{
  margin-bottom:20px!important;
}
body.page-add-fund .amount-label{
  display:block!important;
  color:rgba(255,255,255,0.6)!important;
  font-size:12px!important;
  font-weight:600!important;
  margin-bottom:12px!important;
  text-transform:uppercase!important;
  letter-spacing:0.05em!important;
  text-align:center!important;
}
body.page-add-fund .amount-input-wrapper{
  position:relative!important;
}
body.page-add-fund .add-fund-main-input{
  width:100%!important;
  max-width:100%!important;
  box-sizing:border-box!important;
  background:rgba(0,0,0,0.3)!important;
  border:2px solid rgba(232, 184, 74, 0.4)!important;
  border-radius:20px!important;
  padding:20px 15px!important;
  font-size:32px!important;
  font-weight:800!important;
  color:#fff!important;
  text-align:center!important;
  transition:all 0.3s ease!important;
  outline:none!important;
  box-shadow:inset 0 2px 4px rgba(0,0,0,0.3)!important;
}
body.page-add-fund .add-fund-main-input:focus{
  border-color:#e8b84a!important;
  background:rgba(0,0,0,0.5)!important;
  box-shadow:0 0 20px rgba(232, 184, 74, 0.2), inset 0 2px 4px rgba(0,0,0,0.3)!important;
}
body.page-add-fund .quick-chip-grid{
  display:grid!important;
  grid-template-columns:repeat(4,minmax(0,1fr))!important;
  gap:12px!important;
  margin-bottom:32px!important;
  width:100%!important;
}
body.page-add-fund .quick-chip-grid .chip-item{
  min-width:0!important;
}
body.page-add-fund .amount-chip{
  display:flex!important;
  flex-direction:column!important;
  align-items:center!important;
  justify-content:center!important;
  padding:12px 6px!important;
  background:rgba(255,255,255,0.03)!important;
  border:1px solid rgba(212,136,6,0.15)!important;
  border-radius:16px!important;
  cursor:pointer!important;
  transition:all 0.2s cubic-bezier(0.4, 0, 0.2, 1)!important;
  text-decoration:none!important;
}
body.page-add-fund .amount-chip:hover{
  background:rgba(212,136,6,0.1)!important;
  border-color:rgba(212,136,6,0.6)!important;
  transform:translateY(-2px)!important;
}
body.page-add-fund .amount-chip:active{
  transform:translateY(0)!important;
  background:rgba(212,136,6,0.2)!important;
}
body.page-add-fund .chip-sign{
  font-size:10px!important;
  font-weight:700!important;
  color:#e8b84a!important;
  margin-bottom:2px!important;
}
body.page-add-fund .chip-val{
  font-size:14px!important;
  font-weight:700!important;
  color:#fff!important;
}
body.page-add-fund .method-section{
  margin-bottom:32px!important;
}
body.page-add-fund .method-label{
  display:block!important;
  color:rgba(255,255,255,0.6)!important;
  font-size:12px!important;
  font-weight:600!important;
  margin-bottom:12px!important;
  text-transform:uppercase!important;
  letter-spacing:0.05em!important;
}
body.page-add-fund .custom-select-card{
  position:relative!important;
  display:flex!important;
  align-items:center!important;
  background:rgba(255,255,255,0.03)!important;
  border:1px solid rgba(255,255,255,0.1)!important;
  border-radius:18px!important;
  padding:4px!important;
  transition:all 0.3s ease!important;
  width:100%!important;
  max-width:100%!important;
  min-width:0!important;
  box-sizing:border-box!important;
}
body.page-add-fund .custom-select-card:focus-within{
  border-color:rgba(212,136,6,0.4)!important;
  background:rgba(255,255,255,0.06)!important;
}
body.page-add-fund .select-visual{
  width:44px!important;
  height:44px!important;
  background:rgba(212, 136, 6, 0.15)!important;
  border-radius:14px!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  color:#e8b84a!important;
  font-size:18px!important;
}
body.page-add-fund .add-fund-select-gate{
  flex:1 1 auto!important;
  min-width:0!important;
  width:1%!important;
  max-width:100%!important;
  background:transparent!important;
  border:none!important;
  color:#fff!important;
  font-weight:700!important;
  font-size:13px!important;
  padding:12px 36px 12px 10px!important;
  outline:none!important;
  cursor:pointer!important;
  -webkit-appearance:none!important;
  appearance:none!important;
  box-sizing:border-box!important;
}
body.page-add-fund .select-arrow{
  position:absolute!important;
  right:12px!important;
  top:50%!important;
  transform:translateY(-50%)!important;
  color:rgba(255,255,255,0.3)!important;
  pointer-events:none!important;
  font-size:12px!important;
}
body.page-add-fund .premium-add-btn{
  width:100%!important;
  background:linear-gradient(135deg, #f0d27a 0%, #caa64a 100%)!important;
  border:none!important;
  padding:18px!important;
  border-radius:20px!important;
  color:#1a1200!important;
  font-weight:800!important;
  font-size:16px!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  gap:12px!important;
  box-shadow:0 10px 20px -5px rgba(202, 166, 74, 0.4)!important;
  transition:all 0.3s ease!important;
  cursor:pointer!important;
}
body.page-add-fund .premium-add-btn:hover{
  transform:translateY(-2px)!important;
  box-shadow:0 15px 30px -8px rgba(202, 166, 74, 0.5)!important;
  filter:brightness(1.05)!important;
}
body.page-add-fund .premium-add-btn:active{
  transform:translateY(0)!important;
}
body.page-add-fund .premium-add-btn i{
  transition:transform 0.3s ease!important;
}
body.page-add-fund .premium-add-btn:hover i{
  transform:translateX(5px)!important;
}
body.page-add-fund .add-fund-footer{
  margin-top:40px!important;
  text-align:center!important;
  border-top:1px solid rgba(255,255,255,0.05)!important;
  padding-top:24px!important;
}
body.page-add-fund .help-text{
  font-size:13px!important;
  color:rgba(255,255,255,0.4)!important;
  margin-bottom:12px!important;
}
body.page-add-fund .minimal-wa-link{
  display:inline-flex!important;
  align-items:center!important;
  gap:8px!important;
  color:#25d366!important;
  background:rgba(37, 211, 102, 0.08)!important;
  padding:10px 20px!important;
  border-radius:100px!important;
  font-weight:700!important;
  font-size:13px!important;
  text-decoration:none!important;
  border:1px solid rgba(37, 211, 102, 0.15)!important;
  transition:all 0.2s ease!important;
}
body.page-add-fund .minimal-wa-link:hover{
  background:rgba(37, 211, 102, 0.15)!important;
  transform:translateY(-1px)!important;
}
@media (max-width:400px){
  body.page-add-fund .add-fund-page{
    padding:12px 10px 100px!important;
  }
  body.page-add-fund .add-fund-glass-card{
    padding:22px 14px!important;
    border-radius:20px!important;
  }
  body.page-add-fund .add-fund-title{
    font-size:1.35rem!important;
  }
  body.page-add-fund .add-fund-main-input{
    font-size:26px!important;
    padding:16px 10px!important;
  }
  body.page-add-fund .quick-chip-grid{
    grid-template-columns:repeat(2,minmax(0,1fr))!important;
    gap:10px!important;
  }
  body.page-add-fund .amount-chip{
    padding:10px 4px!important;
  }
  body.page-add-fund .chip-val{
    font-size:13px!important;
  }
  body.page-add-fund .info-bubble{
    font-size:10px!important;
    padding:6px 10px!important;
  }
}
@media (min-width:401px) and (max-width:480px){
  body.page-add-fund .quick-chip-grid{
    grid-template-columns:repeat(4,minmax(0,1fr))!important;
    gap:8px!important;
  }
  body.page-add-fund .amount-chip{
    padding:10px 4px!important;
  }
}

/* single.php — body.page-single-ank (Single Ank bidding; PHP/JS unchanged) */
body.page-single-ank #content{
  background:#000814!important;
  min-height:100vh!important;
  overflow-x:hidden!important;
}
body.page-single-ank #content > .container{
  max-width:520px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding-left:12px!important;
  padding-right:12px!important;
  padding-bottom:88px!important;
  box-sizing:border-box!important;
}
body.page-single-ank .card-full-page{
  background:rgba(10,18,36,.92)!important;
  border:1px solid rgba(232,184,74,.28)!important;
  border-radius:22px!important;
  box-shadow:0 18px 48px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.04)!important;
  padding:18px 14px 28px!important;
  margin:12px 0 32px!important;
  min-height:auto!important;
}
body.page-single-ank .card-full-page .myform > .text-center:first-of-type{
  text-align:center!important;
  display:flex!important;
  flex-direction:column!important;
  align-items:center!important;
  padding:2px 2px 16px!important;
  margin-bottom:4px!important;
  border-bottom:1px solid rgba(232,184,74,.2)!important;
}
body.page-single-ank .card-full-page .myform > .text-center:first-of-type h3{
  color:#fff!important;
  font-size:1.12rem!important;
  font-weight:800!important;
  letter-spacing:.02em!important;
  margin:0 auto 10px!important;
  line-height:1.25!important;
  text-transform:none!important;
  width:100%!important;
}
body.page-single-ank .card-full-page .myform > .text-center:first-of-type .text-muted{
  display:inline-flex!important;
  align-items:center!important;
  justify-content:center!important;
  gap:8px!important;
  margin:0 auto!important;
  padding:8px 12px!important;
  border-radius:10px!important;
  background:rgba(232,184,74,.1)!important;
  border:1px solid rgba(232,184,74,.28)!important;
  color:rgba(255,255,255,.92)!important;
  font-size:12px!important;
  width:fit-content!important;
  max-width:100%!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(.dateGameIDbox){
  display:grid!important;
  grid-template-columns:minmax(0,1.12fr) minmax(0,.88fr)!important;
  gap:12px!important;
  align-items:stretch!important;
  margin:0 0 20px!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(.dateGameIDbox) > .col-6{
  max-width:none!important;
  flex:none!important;
  width:100%!important;
  padding:0!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(.dateGameIDbox) > .col-6:last-child{order:1!important;}
body.page-single-ank .myform > .row.bidoptions-list:has(.dateGameIDbox) > .col-6:first-child{order:2!important;}
body.page-single-ank .myform > .row.bidoptions-list:has(.dateGameIDbox) > .col-6:last-child::before{
  content:"Market"!important;
  display:block!important;
  font-size:10px!important;
  font-weight:800!important;
  letter-spacing:.1em!important;
  color:rgba(232,184,74,.95)!important;
  margin-bottom:6px!important;
  text-transform:uppercase!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(.dateGameIDbox) > .col-6:first-child::before{
  content:"Date"!important;
  display:block!important;
  font-size:10px!important;
  font-weight:800!important;
  letter-spacing:.1em!important;
  color:rgba(232,184,74,.95)!important;
  margin-bottom:6px!important;
  text-transform:uppercase!important;
}
body.page-single-ank .dateGameIDbox,
body.page-single-ank a.dateGameIDbox,
body.page-single-ank select.dateGameIDbox{
  background:rgba(6,12,24,.9)!important;
  border:1px solid rgba(232,184,74,.4)!important;
  border-radius:14px!important;
  color:#fff!important;
  box-shadow:0 4px 14px rgba(0,0,0,.25)!important;
}
body.page-single-ank .dateGameIDbox p{
  color:#fff!important;
  font-weight:700!important;
}
body.page-single-ank select.dateGameIDbox{
  padding:12px 10px!important;
  font-weight:700!important;
  font-size:14px!important;
}
body.page-single-ank select.dateGameIDbox option{
  color:#0f172a!important;
  background:#fff!important;
}
body.page-single-ank hr.devider{
  border:none!important;
  height:1px!important;
  background:linear-gradient(90deg,transparent,rgba(232,184,74,.45),transparent)!important;
  margin:18px 0!important;
  opacity:1!important;
}
body.page-single-ank h3.subheading{
  color:#fff!important;
  font-size:.82rem!important;
  font-weight:800!important;
  letter-spacing:.12em!important;
  text-transform:uppercase!important;
  text-align:left!important;
  margin:16px 0 12px!important;
  padding:8px 0 8px 14px!important;
  border-left:4px solid var(--nav-gold,#e8b84a)!important;
  background:linear-gradient(90deg,rgba(232,184,74,.08),transparent)!important;
  border-radius:0 10px 10px 0!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(a.bidamtbox){
  display:grid!important;
  grid-template-columns:repeat(4,minmax(0,1fr))!important;
  gap:10px!important;
  margin-left:0!important;
  margin-right:0!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(a.bidamtbox) + .row.bidoptions-list:has(a.bidamtbox){
  margin-top:6px!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(a.bidamtbox) > .col-3{
  width:100%!important;
  max-width:none!important;
  flex:none!important;
  padding:0!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(.bidinputdiv){
  display:grid!important;
  grid-template-columns:repeat(4,minmax(0,1fr))!important;
  gap:8px!important;
  margin-left:0!important;
  margin-right:0!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(.bidinputdiv) > .col-3{
  width:100%!important;
  max-width:none!important;
  flex:none!important;
  padding:2px!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(.btn-streched){
  display:grid!important;
  grid-template-columns:1fr 1.28fr!important;
  gap:12px!important;
  margin-top:4px!important;
  margin-left:0!important;
  margin-right:0!important;
}
body.page-single-ank .myform > .row.bidoptions-list:has(.btn-streched) > .col-6{
  width:100%!important;
  max-width:none!important;
  flex:none!important;
  padding:0!important;
}
body.page-single-ank .myform > .tbmar-20.text-center{
  text-align:left!important;
  margin-top:16px!important;
  margin-bottom:14px!important;
  padding:12px 14px!important;
  border-radius:14px!important;
  background:rgba(0,0,0,.32)!important;
  border:1px solid rgba(232,184,74,.22)!important;
}
body.page-single-ank a.bidamtbox{
  display:block!important;
  background:rgba(8,14,28,.95)!important;
  border:1px solid rgba(232,184,74,.35)!important;
  border-radius:14px!important;
  padding:12px 6px!important;
  margin-bottom:0!important;
  box-shadow:0 4px 12px rgba(0,0,0,.3)!important;
  transition:border-color .2s,transform .2s,box-shadow .2s!important;
}
body.page-single-ank a.bidamtbox p,
body.page-single-ank a.bidamtbox p .fa-inr{
  color:#fff!important;
  font-weight:800!important;
  font-size:14px!important;
}
body.page-single-ank a.bidamtbox:hover,
body.page-single-ank a.bidamtbox:active{
  border-color:rgba(232,184,74,.85)!important;
  transform:translateY(-1px)!important;
  box-shadow:0 8px 20px rgba(0,0,0,.35)!important;
}
body.page-single-ank a.bidamtbox.active{
  background:linear-gradient(145deg,#f0d27a,#caa64a)!important;
  border-color:rgba(255,255,255,.25)!important;
  box-shadow:0 8px 22px rgba(202,166,74,.35)!important;
}
body.page-single-ank a.bidamtbox.active p,
body.page-single-ank a.bidamtbox.active p .fa-inr{
  color:#1a1200!important;
}
body.page-single-ank .bidinputdiv{
  margin:0 0 10px!important;
  padding:10px 8px 12px!important;
  background:rgba(6,12,24,.75)!important;
  border:1px solid rgba(232,184,74,.22)!important;
  border-radius:14px!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.04)!important;
}
body.page-single-ank .bidinputdiv lable,
body.page-single-ank .bidinputdiv label{
  color:var(--nav-gold,#e8b84a)!important;
  font-size:15px!important;
  font-weight:800!important;
  margin-bottom:8px!important;
}
body.page-single-ank .bidinputdiv .pointinputbox{
  background:rgba(0,0,0,.35)!important;
  border:2px solid rgba(232,184,74,.35)!important;
  color:#fff!important;
  border-radius:12px!important;
  padding:12px 8px!important;
  font-size:17px!important;
}
body.page-single-ank .bidinputdiv .pointinputbox:focus{
  border-color:#e8b84a!important;
  background:rgba(0,0,0,.5)!important;
  box-shadow:0 0 0 3px rgba(232,184,74,.15)!important;
}
body.page-single-ank .tbmar-20.text-center p{
  color:rgba(255,255,255,.88)!important;
  font-size:14px!important;
  font-weight:600!important;
  display:flex!important;
  align-items:center!important;
  justify-content:space-between!important;
  gap:12px!important;
  width:100%!important;
  margin:0!important;
  flex-wrap:wrap!important;
}
body.page-single-ank #total_point2{
  color:#1a1200!important;
  background:linear-gradient(145deg,#f0d27a,#caa64a)!important;
  padding:6px 16px!important;
  border-radius:10px!important;
  display:inline-block!important;
  font-weight:800!important;
  font-size:18px!important;
  box-shadow:0 4px 12px rgba(202,166,74,.3)!important;
}
body.page-single-ank .btn-streched.btn-light{
  background:rgba(255,255,255,.06)!important;
  border:1px solid rgba(232,184,74,.45)!important;
  color:#fff!important;
}
body.page-single-ank .btn-streched.btn-light:hover{
  background:rgba(232,184,74,.12)!important;
  border-color:rgba(232,184,74,.75)!important;
  color:#fff!important;
}
body.page-single-ank .btn-streched.btn-theme{
  background:linear-gradient(145deg,#f0d27a,#caa64a)!important;
  border:none!important;
  color:#1a1200!important;
  box-shadow:0 8px 22px rgba(202,166,74,.35)!important;
}
body.page-single-ank .btn-streched.btn-theme:hover{
  filter:brightness(1.06)!important;
  color:#1a1200!important;
}
body.page-single-ank .tbmar-40.text-center p{
  color:rgba(255,255,255,.85)!important;
  line-height:1.5!important;
}
@media (max-width:360px){
  body.page-single-ank .card-full-page{
    padding:14px 10px 24px!important;
    border-radius:18px!important;
  }
  body.page-single-ank a.bidamtbox{
    padding:10px 4px!important;
  }
  body.page-single-ank a.bidamtbox p{
    font-size:13px!important;
  }
  body.page-single-ank .myform > .row.bidoptions-list:has(.bidinputdiv){
    grid-template-columns:repeat(4,minmax(0,1fr))!important;
    gap:6px!important;
  }
  body.page-single-ank .bidinputdiv{
    padding:8px 6px 10px!important;
  }
  body.page-single-ank .bidinputdiv .pointinputbox{
    font-size:15px!important;
    padding:10px 6px!important;
  }
}

/* jodi.php — same layout as single.php (body.page-single-ank) + compact 00–99 grid */
body.page-single-ank.page-jodi .myform > .row.bidoptions-list:has(.bidinputdiv){
  gap:5px!important;
}
body.page-single-ank.page-jodi .myform > .row.bidoptions-list:has(.bidinputdiv) > .col-3{
  padding:1px!important;
}
body.page-single-ank.page-jodi .bidinputdiv{
  margin:0 0 6px!important;
  padding:6px 4px 8px!important;
}
body.page-single-ank.page-jodi .bidinputdiv lable,
body.page-single-ank.page-jodi .bidinputdiv label{
  font-size:11px!important;
  font-weight:800!important;
  margin-bottom:4px!important;
}
body.page-single-ank.page-jodi .bidinputdiv .pointinputbox{
  font-size:12px!important;
  padding:7px 4px!important;
}
@media (max-width:360px){
  body.page-single-ank.page-jodi .bidinputdiv lable,
  body.page-single-ank.page-jodi .bidinputdiv label{
    font-size:10px!important;
  }
  body.page-single-ank.page-jodi .bidinputdiv .pointinputbox{
    font-size:11px!important;
    padding:6px 3px!important;
  }
}

/* single-patti / double-patti / triple-patti / half-sangam / full-sangam — same premium UI as single.php */
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) #content{
  background:#000814!important;
  min-height:100vh!important;
  overflow-x:hidden!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) #content > .container{
  max-width:520px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding-left:12px!important;
  padding-right:12px!important;
  padding-bottom:88px!important;
  box-sizing:border-box!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .card-full-page{
  background:rgba(10,18,36,.92)!important;
  border:1px solid rgba(232,184,74,.28)!important;
  border-radius:22px!important;
  box-shadow:0 18px 48px rgba(0,0,0,.45),inset 0 1px 0 rgba(255,255,255,.04)!important;
  padding:18px 14px 28px!important;
  margin:12px 0 32px!important;
  min-height:auto!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .card-full-page .myform > .text-center:first-of-type{
  text-align:center!important;
  display:flex!important;
  flex-direction:column!important;
  align-items:center!important;
  padding:2px 2px 16px!important;
  margin-bottom:4px!important;
  border-bottom:1px solid rgba(232,184,74,.2)!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .card-full-page .myform > .text-center:first-of-type h3{
  color:#fff!important;
  font-size:1.12rem!important;
  font-weight:800!important;
  letter-spacing:.02em!important;
  margin:0 auto 10px!important;
  line-height:1.25!important;
  text-transform:none!important;
  width:100%!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .card-full-page .myform > .text-center:first-of-type .text-muted{
  display:inline-flex!important;
  align-items:center!important;
  justify-content:center!important;
  gap:8px!important;
  margin:0 auto!important;
  padding:8px 12px!important;
  border-radius:10px!important;
  background:rgba(232,184,74,.1)!important;
  border:1px solid rgba(232,184,74,.28)!important;
  color:rgba(255,255,255,.92)!important;
  font-size:12px!important;
  width:fit-content!important;
  max-width:100%!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.dateGameIDbox){
  display:grid!important;
  grid-template-columns:minmax(0,1.12fr) minmax(0,.88fr)!important;
  gap:12px!important;
  align-items:stretch!important;
  margin:0 0 20px!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.dateGameIDbox) > .col-6{
  max-width:none!important;
  flex:none!important;
  width:100%!important;
  padding:0!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.dateGameIDbox) > .col-6:last-child{order:1!important;}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.dateGameIDbox) > .col-6:first-child{order:2!important;}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.dateGameIDbox) > .col-6:last-child::before{
  content:"Market"!important;
  display:block!important;
  font-size:10px!important;
  font-weight:800!important;
  letter-spacing:.1em!important;
  color:rgba(232,184,74,.95)!important;
  margin-bottom:6px!important;
  text-transform:uppercase!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.dateGameIDbox) > .col-6:first-child::before{
  content:"Date"!important;
  display:block!important;
  font-size:10px!important;
  font-weight:800!important;
  letter-spacing:.1em!important;
  color:rgba(232,184,74,.95)!important;
  margin-bottom:6px!important;
  text-transform:uppercase!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .dateGameIDbox,
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.dateGameIDbox,
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) select.dateGameIDbox{
  background:rgba(6,12,24,.9)!important;
  border:1px solid rgba(232,184,74,.4)!important;
  border-radius:14px!important;
  color:#fff!important;
  box-shadow:0 4px 14px rgba(0,0,0,.25)!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .dateGameIDbox p{
  color:#fff!important;
  font-weight:700!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) select.dateGameIDbox{
  padding:12px 10px!important;
  font-weight:700!important;
  font-size:14px!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) select.dateGameIDbox option{
  color:#0f172a!important;
  background:#fff!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) hr.devider{
  border:none!important;
  height:1px!important;
  background:linear-gradient(90deg,transparent,rgba(232,184,74,.45),transparent)!important;
  margin:18px 0!important;
  opacity:1!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) h3.subheading{
  color:#fff!important;
  font-size:.82rem!important;
  font-weight:800!important;
  letter-spacing:.12em!important;
  text-transform:uppercase!important;
  text-align:left!important;
  margin:16px 0 12px!important;
  padding:8px 0 8px 14px!important;
  border-left:4px solid var(--nav-gold,#e8b84a)!important;
  background:linear-gradient(90deg,rgba(232,184,74,.08),transparent)!important;
  border-radius:0 10px 10px 0!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(a.bidamtbox){
  display:grid!important;
  grid-template-columns:repeat(4,minmax(0,1fr))!important;
  gap:10px!important;
  margin-left:0!important;
  margin-right:0!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(a.bidamtbox) + .row.bidoptions-list:has(a.bidamtbox){
  margin-top:6px!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(a.bidamtbox) > .col-3{
  width:100%!important;
  max-width:none!important;
  flex:none!important;
  padding:0!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.bidinputdiv){
  display:grid!important;
  grid-template-columns:repeat(4,minmax(0,1fr))!important;
  gap:8px!important;
  margin-left:0!important;
  margin-right:0!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.bidinputdiv) > .col-3{
  width:100%!important;
  max-width:none!important;
  flex:none!important;
  padding:2px!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.btn-streched){
  display:grid!important;
  grid-template-columns:1fr 1.28fr!important;
  gap:12px!important;
  margin-top:4px!important;
  margin-left:0!important;
  margin-right:0!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.btn-streched) > .col-6{
  width:100%!important;
  max-width:none!important;
  flex:none!important;
  padding:0!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .tbmar-20.text-center{
  text-align:left!important;
  margin-top:16px!important;
  margin-bottom:14px!important;
  padding:12px 14px!important;
  border-radius:14px!important;
  background:rgba(0,0,0,.32)!important;
  border:1px solid rgba(232,184,74,.22)!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.bidamtbox{
  display:block!important;
  background:rgba(8,14,28,.95)!important;
  border:1px solid rgba(232,184,74,.35)!important;
  border-radius:14px!important;
  padding:12px 6px!important;
  margin-bottom:0!important;
  box-shadow:0 4px 12px rgba(0,0,0,.3)!important;
  transition:border-color .2s,transform .2s,box-shadow .2s!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.bidamtbox p,
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.bidamtbox p .fa-inr{
  color:#fff!important;
  font-weight:800!important;
  font-size:14px!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.bidamtbox:hover,
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.bidamtbox:active{
  border-color:rgba(232,184,74,.85)!important;
  transform:translateY(-1px)!important;
  box-shadow:0 8px 20px rgba(0,0,0,.35)!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.bidamtbox.active{
  background:linear-gradient(145deg,#f0d27a,#caa64a)!important;
  border-color:rgba(255,255,255,.25)!important;
  box-shadow:0 8px 22px rgba(202,166,74,.35)!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.bidamtbox.active p,
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.bidamtbox.active p .fa-inr{
  color:#1a1200!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .bidinputdiv{
  margin:0 0 10px!important;
  padding:10px 8px 12px!important;
  background:rgba(6,12,24,.75)!important;
  border:1px solid rgba(232,184,74,.22)!important;
  border-radius:14px!important;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.04)!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .bidinputdiv lable,
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .bidinputdiv label{
  color:var(--nav-gold,#e8b84a)!important;
  font-size:15px!important;
  font-weight:800!important;
  margin-bottom:8px!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .bidinputdiv .pointinputbox{
  background:rgba(0,0,0,.35)!important;
  border:2px solid rgba(232,184,74,.35)!important;
  color:#fff!important;
  border-radius:12px!important;
  padding:12px 8px!important;
  font-size:17px!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .bidinputdiv .pointinputbox:focus{
  border-color:#e8b84a!important;
  background:rgba(0,0,0,.5)!important;
  box-shadow:0 0 0 3px rgba(232,184,74,.15)!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .tbmar-20.text-center p{
  color:rgba(255,255,255,.88)!important;
  font-size:14px!important;
  font-weight:600!important;
  display:flex!important;
  align-items:center!important;
  justify-content:space-between!important;
  gap:12px!important;
  width:100%!important;
  margin:0!important;
  flex-wrap:wrap!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) #total_point2{
  color:#1a1200!important;
  background:linear-gradient(145deg,#f0d27a,#caa64a)!important;
  padding:6px 16px!important;
  border-radius:10px!important;
  display:inline-block!important;
  font-weight:800!important;
  font-size:18px!important;
  box-shadow:0 4px 12px rgba(202,166,74,.3)!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .btn-streched.btn-light{
  background:rgba(255,255,255,.06)!important;
  border:1px solid rgba(232,184,74,.45)!important;
  color:#fff!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .btn-streched.btn-light:hover{
  background:rgba(232,184,74,.12)!important;
  border-color:rgba(232,184,74,.75)!important;
  color:#fff!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .btn-streched.btn-theme{
  background:linear-gradient(145deg,#f0d27a,#caa64a)!important;
  border:none!important;
  color:#1a1200!important;
  box-shadow:0 8px 22px rgba(202,166,74,.35)!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .btn-streched.btn-theme:hover{
  filter:brightness(1.06)!important;
  color:#1a1200!important;
}
:is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .tbmar-40.text-center p{
  color:rgba(255,255,255,.85)!important;
  line-height:1.5!important;
}
@media (max-width:360px){
  :is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .card-full-page{
    padding:14px 10px 24px!important;
    border-radius:18px!important;
  }
  :is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.bidamtbox{
    padding:10px 4px!important;
  }
  :is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) a.bidamtbox p{
    font-size:13px!important;
  }
  :is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .myform > .row.bidoptions-list:has(.bidinputdiv){
    grid-template-columns:repeat(4,minmax(0,1fr))!important;
    gap:6px!important;
  }
  :is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .bidinputdiv{
    padding:8px 6px 10px!important;
  }
  :is(body.page-single-patti,body.page-double-patti,body.page-triple-patti,body.page-half-sangam,body.page-full-sangam) .bidinputdiv .pointinputbox{
    font-size:15px!important;
    padding:10px 6px!important;
  }
}

/* half-sangam + full-sangam — 3-column bid rows; inputs type=number (not .pointinputbox) */
body.page-half-sangam .myform > .row.bidoptions-list:has(.bidinputdiv),
body.page-full-sangam .myform > .row.bidoptions-list:has(.bidinputdiv){
  grid-template-columns:repeat(3,minmax(0,1fr))!important;
}
body.page-half-sangam .myform > .row.bidoptions-list:has(.bidinputdiv) > .col-4,
body.page-full-sangam .myform > .row.bidoptions-list:has(.bidinputdiv) > .col-4{
  width:100%!important;
  max-width:none!important;
  flex:none!important;
  padding:4px!important;
}
body.page-half-sangam .bidinputdiv input[type="number"],
body.page-full-sangam .bidinputdiv input[type="number"]{
  background:rgba(0,0,0,.35)!important;
  border:2px solid rgba(232,184,74,.35)!important;
  color:#fff!important;
  border-radius:12px!important;
  padding:12px 8px!important;
  font-size:16px!important;
  width:100%!important;
  box-sizing:border-box!important;
}
body.page-half-sangam .bidinputdiv input[type="number"]:focus,
body.page-full-sangam .bidinputdiv input[type="number"]:focus{
  border-color:#e8b84a!important;
  background:rgba(0,0,0,.5)!important;
  outline:none!important;
  box-shadow:0 0 0 3px rgba(232,184,74,.15)!important;
}
body.page-half-sangam .bidinputdiv,
body.page-full-sangam .bidinputdiv{
  margin:0 0 4px!important;
}
body.page-half-sangam .myform > .row.sangam-form-actions,
body.page-full-sangam .myform > .row.sangam-form-actions{
  margin-top:8px!important;
}
@media (max-width:360px){
  body.page-half-sangam .myform > .row.bidoptions-list:has(.bidinputdiv),
  body.page-full-sangam .myform > .row.bidoptions-list:has(.bidinputdiv){
    gap:6px!important;
  }
  body.page-half-sangam .bidinputdiv input[type="number"],
  body.page-full-sangam .bidinputdiv input[type="number"]{
    font-size:14px!important;
    padding:10px 6px!important;
  }
}

/* starline-play.php — body.page-starline-play */
body.page-starline-play #content{
  background:#000814!important;
  min-height:100vh!important;
  overflow-x:hidden!important;
}
body.page-starline-play #content > .container{
  max-width:480px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding-left:14px!important;
  padding-right:14px!important;
  padding-bottom:88px!important;
  box-sizing:border-box!important;
  position:relative!important;
  z-index:1!important;
}
body.page-starline-play #content > .container + .container{
  z-index:2!important;
}
body.page-starline-play #scroll-container.noticebr{
  background:rgba(10,18,36,.85)!important;
  border:1px solid rgba(232,184,74,.25)!important;
  border-radius:14px!important;
  box-shadow:0 8px 24px rgba(0,0,0,.35)!important;
  margin:10px 14px 14px!important;
  padding:10px 12px!important;
}
body.page-starline-play #scroll-container .notice-scroll-seg{
  color:rgba(255,255,255,.88)!important;
}
body.page-starline-play .tb-10 > .row:has(a.home-sl-box){
  display:flex!important;
  flex-direction:column!important;
  flex-wrap:nowrap!important;
  gap:12px!important;
  margin:0 0 16px!important;
  margin-left:0!important;
  margin-right:0!important;
}
body.page-starline-play .tb-10 > .row:has(a.home-sl-box) > [class*="col-"]{
  padding-left:0!important;
  padding-right:0!important;
  flex:0 0 auto!important;
  width:100%!important;
  max-width:100%!important;
  min-width:0!important;
}
body.page-starline-play a.home-sl-box{
  position:relative!important;
  display:flex!important;
  flex-direction:column!important;
  align-items:flex-start!important;
  justify-content:center!important;
  text-align:left!important;
  padding:14px 38px 14px 16px!important;
  margin-bottom:0!important;
  border-radius:14px!important;
  background:rgba(10,18,36,.92)!important;
  border:1px solid rgba(232,184,74,.42)!important;
  box-shadow:0 6px 20px rgba(0,0,0,.35),inset 0 1px 0 rgba(255,255,255,.04)!important;
  color:#fff!important;
  font-weight:700!important;
  font-size:15px!important;
  line-height:1.3!important;
  text-decoration:none!important;
}
body.page-starline-play a.home-sl-box br{
  display:none!important;
}
body.page-starline-play a.home-sl-box span{
  display:block!important;
  font-size:12px!important;
  font-weight:600!important;
  color:rgba(232,184,74,.9)!important;
  margin-top:4px!important;
}
body.page-starline-play a.home-sl-box::after{
  content:"\f054"!important;
  font-family:FontAwesome!important;
  position:absolute!important;
  right:16px!important;
  top:50%!important;
  transform:translateY(-50%)!important;
  font-size:12px!important;
  color:var(--nav-gold,#e8b84a)!important;
  opacity:.9!important;
}
body.page-starline-play .tb-10 > .row.game-list-inner{
  display:grid!important;
  grid-template-columns:minmax(0,1fr) auto minmax(72px,auto)!important;
  align-items:center!important;
  gap:10px 12px!important;
  margin:10px 0!important;
  margin-left:0!important;
  margin-right:0!important;
  padding:12px 18px!important;
  width:100%!important;
  max-width:100%!important;
  background:#fff!important;
  border-radius:999px!important;
  border:none!important;
  box-shadow:0 4px 16px rgba(0,0,0,.18)!important;
}
body.page-starline-play .tb-10 > .row.game-list-inner > [class*="col-"]{
  width:auto!important;
  max-width:none!important;
  flex:0 1 auto!important;
  padding:0!important;
  text-align:left!important;
  min-width:0!important;
}
body.page-starline-play .starline-play-empty{
  color:rgba(255,255,255,.65)!important;
  font-size:14px!important;
  padding:28px 16px!important;
  margin:8px 0!important;
  border:1px dashed rgba(232,184,74,.35)!important;
  border-radius:16px!important;
  background:rgba(10,18,36,.5)!important;
}
body.page-starline-play .sgameName{
  font-size:15px!important;
  font-weight:700!important;
  color:#0f172a!important;
  padding:0!important;
  display:block!important;
}
body.page-starline-play .sgameResut{
  font-size:18px!important;
  font-weight:800!important;
  letter-spacing:.06em!important;
  color:#1e3a8a!important;
  padding:0!important;
  text-align:right!important;
  display:block!important;
}
body.page-starline-play .splaydiv{
  text-align:center!important;
  padding:0!important;
}
body.page-starline-play a.sgame-play{
  display:inline-flex!important;
  align-items:center!important;
  justify-content:center!important;
  padding:8px 14px!important;
  border-radius:999px!important;
  font-size:12px!important;
  font-weight:800!important;
  text-transform:uppercase!important;
  letter-spacing:.04em!important;
  background:linear-gradient(145deg,#f0d27a,#caa64a)!important;
  color:#1a1200!important;
  border:1px solid rgba(255,255,255,.35)!important;
  box-shadow:0 4px 12px rgba(202,166,74,.35)!important;
  text-decoration:none!important;
}
body.page-starline-play a.sgame-play:hover{
  filter:brightness(1.05)!important;
}
@media (max-width:360px){
  body.page-starline-play a.home-sl-box{
    font-size:14px!important;
    padding:12px 34px 12px 14px!important;
  }
  body.page-starline-play .tb-10 > .row.game-list-inner{
    grid-template-columns:minmax(0,1fr) auto minmax(64px,auto)!important;
    padding:10px 14px!important;
  }
  body.page-starline-play .sgameResut{
    font-size:16px!important;
  }
}

/* bidding-history + starline + fund-history + transaction-history + withdraw — shared dark/orange card layout */
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) #content{
  background:#050b1a!important;
  min-height:100vh!important;
  overflow-x:hidden!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) #content > .container{
  max-width:520px!important;
  margin-left:auto!important;
  margin-right:auto!important;
  padding:12px 14px 96px!important;
  box-sizing:border-box!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .text-center.tb-10:first-of-type{
  margin-bottom:14px!important;
  padding-bottom:8px!important;
  border-bottom:1px solid rgba(230,138,0,.2)!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .text-center.tb-10:first-of-type .gdash3{
  color:var(--nav-gold,#e8b84a)!important;
  font-weight:800!important;
  font-size:1.25rem!important;
  margin-bottom:6px!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .text-center.tb-10:first-of-type span{
  color:#fff!important;
  font-size:12px!important;
  opacity:1!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card.shadow-sm{
  background:#0a1c3d!important;
  border:1px solid rgba(59,130,246,.22)!important;
  border-radius:16px!important;
  box-shadow:0 8px 24px rgba(0,0,0,.4)!important;
  margin-bottom:14px!important;
  overflow:hidden!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-header{
  background:transparent!important;
  border:0!important;
  border-bottom:1px solid rgba(255,255,255,.08)!important;
  padding:14px 14px 10px!important;
  border-radius:0!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-header h6{
  color:#e8952a!important;
  font-weight:800!important;
  font-size:15px!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-header h6 .badge{
  background:rgba(0,0,0,.25)!important;
  color:#e8952a!important;
  border:1px solid rgba(230,149,42,.45)!important;
  font-weight:700!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-header > span.text-muted{
  color:rgba(255,255,255,.45)!important;
  font-size:11px!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-body{
  background:transparent!important;
  padding:14px!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-body .row.text-center .text-muted{
  color:rgba(255,255,255,.55)!important;
  font-size:11px!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-body .row.text-center h4{
  color:#e8952a!important;
  font-weight:800!important;
  font-size:1.15rem!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-body .border-right{
  border-color:rgba(255,255,255,.1)!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-body .border-top{
  border-color:rgba(255,255,255,.1)!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-body .d-flex small.text-muted{
  color:rgba(255,255,255,.55)!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-body .d-flex small.text-warning{
  color:#fbbf24!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-body .d-flex small.text-danger{
  color:#f87171!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 .card .card-body .d-flex small.text-success{
  color:#4ade80!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 > a.btn.btn-theme{
  float:none!important;
  display:inline-block!important;
  width:calc(50% - 10px)!important;
  margin:10px 5px 0!important;
  padding:12px 10px!important;
  text-align:center!important;
  border-radius:12px!important;
  font-weight:800!important;
  background:linear-gradient(180deg,#f0a030,#d97706)!important;
  border:none!important;
  color:#fff!important;
  box-shadow:0 6px 18px rgba(217,119,6,.35)!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 > a.btn.btn-theme.disabled{
  opacity:.45!important;
  pointer-events:none!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10::after{
  content:""!important;
  display:table!important;
  clear:both!important;
}
:is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tbmar-40.text-center p{
  color:rgba(255,255,255,.7)!important;
  padding:24px 12px!important;
  border:1px dashed rgba(230,149,42,.35)!important;
  border-radius:14px!important;
  background:rgba(10,28,61,.5)!important;
}
@media (max-width:380px){
  :is(body.page-bidding-history-main,body.page-bidding-history-starline,body.page-fund-history,body.page-transaction-history,body.page-withdraw) .tb-10 > a.btn.btn-theme{
    width:100%!important;
    margin:8px 0!important;
  }
}
body.page-bidding-history-starline .text-center.tb-10:first-of-type{
  margin-top:20px!important;
  padding-top:6px!important;
}
body.page-bidding-history-main .text-center.tb-10:first-of-type{
  margin-top:20px!important;
  padding-top:6px!important;
}
body.page-fund-history .text-center.tb-10:first-of-type{
  margin-top:20px!important;
  padding-top:6px!important;
}
body.page-fund-history .tb-10 .card .card-body h6.font-weight-bold.text-dark{
  color:#fff!important;
}
body.page-fund-history .tb-10 .card .card-body small.text-muted{
  color:rgba(255,255,255,.58)!important;
}
body.page-fund-history .tb-10 .card .card-body .text-right h6.font-weight-bold{
  font-weight:800!important;
}
body.page-fund-history .tb-10 .card .card-body .border-top{
  border-color:rgba(255,255,255,.1)!important;
}
body.page-fund-history .tb-10 .card .card-body .border-top .text-muted{
  color:rgba(255,255,255,.52)!important;
}
body.page-fund-history .tb-10 .card .card-body .border-top .text-warning{
  color:#fbbf24!important;
}
body.page-fund-history .tb-10 .card .card-body .border-top .text-success{
  color:#4ade80!important;
}
body.page-fund-history .tb-10 .card .card-body .border-top .text-danger{
  color:#f87171!important;
}
body.page-transaction-history .text-center.tb-10:first-of-type{
  margin-top:20px!important;
  padding-top:6px!important;
}
body.page-transaction-history .tb-10 .card .card-body h6.font-weight-bold.text-dark{
  color:#fff!important;
}
body.page-transaction-history .tb-10 .card .card-body small.text-muted{
  color:rgba(255,255,255,.58)!important;
}
body.page-transaction-history .tb-10 .card .card-body .text-right h6.font-weight-bold{
  font-weight:800!important;
}
body.page-transaction-history .tb-10 .card .card-body > .d-flex > .d-flex.align-items-center > div:first-child{
  background:rgba(255,255,255,.08)!important;
  border:1px solid rgba(255,255,255,.12)!important;
}
body.page-transaction-history .tb-10 .card .card-body .border-top{
  border-color:rgba(255,255,255,.1)!important;
}
body.page-transaction-history .tb-10 .card .card-body .border-top .text-muted{
  color:#e8952a!important;
  font-weight:600!important;
}
body.page-transaction-history .tb-10 .card .card-body .border-top .font-weight-bold.text-secondary{
  color:rgba(255,255,255,.9)!important;
}
body.page-withdraw .text-center.tb-10:first-of-type{
  margin-top:20px!important;
  padding-top:6px!important;
}
body.page-withdraw .withdraw-total-summary{
  border-color:rgba(255,255,255,.12)!important;
}
body.page-withdraw .withdraw-total-summary .text-muted{
  color:rgba(255,255,255,.55)!important;
  font-size:12px!important;
}
body.page-withdraw .withdraw-total-amt{
  color:#e8952a!important;
  font-weight:800!important;
  font-size:1.35rem!important;
  letter-spacing:.02em!important;
}
body.page-withdraw .withdraw-page-icon{
  width:56px!important;
  height:56px!important;
  border-radius:50%!important;
  display:flex!important;
  align-items:center!important;
  justify-content:center!important;
  margin:0 auto!important;
  background:rgba(255,255,255,.08)!important;
  border:1px solid rgba(255,255,255,.12)!important;
  color:#f87171!important;
  font-size:24px!important;
}
body.page-withdraw .withdraw-alert-info{
  border-radius:12px!important;
  font-size:12px!important;
  line-height:1.45!important;
  background:rgba(251,191,36,.12)!important;
  border:1px solid rgba(251,191,36,.35)!important;
  color:#fef3c7!important;
  margin-bottom:14px!important;
  padding:12px 14px!important;
}
body.page-withdraw .withdraw-alert-info .text-danger{
  color:#fca5a5!important;
}
body.page-withdraw .withdraw-main-form{
  display:block!important;
  margin-top:0!important;
  margin-bottom:0!important;
  padding-top:0!important;
}
body.page-withdraw .withdraw-main-form > * + *{
  margin-top:14px!important;
}
body.page-withdraw .withdraw-alert-bank{
  border-radius:12px!important;
  background:rgba(248,113,113,.12)!important;
  border:1px solid rgba(248,113,113,.35)!important;
  color:#fecaca!important;
  margin-bottom:0!important;
  padding:12px 14px!important;
}
body.page-withdraw .withdraw-support{
  margin-top:20px!important;
  padding-top:6px!important;
}
body.page-withdraw .withdraw-label-amt{
  color:rgba(255,255,255,.72)!important;
  font-size:14px!important;
}
body.page-withdraw .withdraw-input-amt{
  font-size:22px!important;
  font-weight:800!important;
  padding:14px!important;
  border-radius:14px!important;
  background:rgba(255,255,255,.06)!important;
  border:1px solid rgba(255,255,255,.18)!important;
  color:#fff!important;
}
body.page-withdraw .withdraw-input-amt::placeholder{
  color:rgba(255,255,255,.4)!important;
}
body.page-withdraw .withdraw-submit-btn{
  border-radius:14px!important;
  font-size:16px!important;
}
body.page-withdraw .withdraw-help-text{
  color:rgba(255,255,255,.55)!important;
  font-size:13px!important;
}
body.page-withdraw .withdraw-btn-outline{
  border-radius:14px!important;
  border-width:2px!important;
  border-color:rgba(248,113,113,.55)!important;
  color:#fecaca!important;
  background:transparent!important;
  display:block!important;
  padding:12px 14px!important;
  box-sizing:border-box!important;
}
body.page-withdraw .withdraw-btn-wa{
  border-radius:14px!important;
  border:1px solid rgba(255,255,255,.22)!important;
  color:#fff!important;
  background:rgba(255,255,255,.05)!important;
}
body.page-withdraw .withdraw-btn-wa:hover{
  background:rgba(255,255,255,.1)!important;
  color:#fff!important;
}

/* App Download Popup (Noticeboard) - Premium Centered Stacked Design */
.modern-popup.modal .modal-dialog-centered {
    display: flex !important;
    align-items: center !important;
    min-height: calc(100% - 1rem) !important;
    justify-content: center !important;
}

.modern-popup .modal-content {
    background: linear-gradient(145deg, #0f1f3d 0%, #050a14 100%) !important;
    border: 1px solid rgba(232, 184, 74, 0.4) !important;
    border-radius: 30px !important;
    overflow: hidden;
    box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.8) !important;
    text-align: center !important;
}

.modern-popup .modal-header {
    background: linear-gradient(90deg, #caa64a 0%, #d4a84a 50%, #caa64a 100%) !important;
    border: none !important;
    padding: 12px 24px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    position: relative !important;
    border-radius: 0 !important;
}

.modern-popup .modal-header .modal-title {
    color: #1a1200 !important;
    font-weight: 800 !important;
    font-size: 14px !important;
    letter-spacing: 1px !important;
    text-transform: uppercase !important;
    margin: 0 !important;
    width: 100% !important;
    text-align: center !important;
}

.modern-popup .modal-header .close {
    position: absolute !important;
    right: 15px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    color: #1a1200 !important;
    opacity: 0.7 !important;
    margin: 0 !important;
    padding: 0 !important;
    font-size: 24px !important;
    background: transparent !important;
    border: none !important;
    line-height: 1 !important;
}

.modern-popup .modal-body {
    padding: 25px 24px !important;
    background: transparent !important;
}

.popup-image-centered {
    width: 130px;
    margin: 0 auto 20px;
    position: relative;
    z-index: 1;
}

.popup-image-centered img {
    width: 100%;
    height: auto;
    filter: drop-shadow(0 15px 30px rgba(0,0,0,0.5));
    border-radius: 20px;
}

.popup-image-centered::after {
    content: "";
    position: absolute;
    inset: -20px;
    background: radial-gradient(circle, rgba(202, 166, 74, 0.2) 0%, transparent 70%);
    z-index: -1;
}

.popup-content-centered h3 {
    color: #caa64a !important;
    font-weight: 900 !important;
    font-size: 24px !important;
    margin-bottom: 12px !important;
    letter-spacing: 1px !important;
    text-transform: uppercase !important;
}

.popup-content-centered p {
    color: rgba(255, 255, 255, 0.85) !important;
    font-size: 15px !important;
    line-height: 1.6 !important;
    margin-bottom: 30px !important;
    max-width: 320px;
    margin-left: auto;
    margin-right: auto;
}

.popup-btn-centered {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 12px !important;
    background: linear-gradient(180deg, #f0d27a 0%, #caa64a 100%) !important;
    color: #1a1200 !important;
    font-weight: 900 !important;
    padding: 15px 40px !important;
    border-radius: 50px !important;
    text-decoration: none !important;
    font-size: 16px !important;
    box-shadow: 0 10px 25px rgba(202, 166, 74, 0.4) !important;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    width: auto !important;
    border: none !important;
}

.popup-btn-centered:hover {
    transform: scale(1.05) translateY(-3px) !important;
    box-shadow: 0 15px 35px rgba(202, 166, 74, 0.5) !important;
    color: #1a1200 !important;
}

@media (max-width: 480px) {
    .modern-popup .modal-dialog {
        margin: 1.5rem !important;
        max-width: none !important;
    }
    .popup-content-centered h3 {
        font-size: 20px !important;
    }
    .popup-content-centered p {
        font-size: 14px !important;
    }
}

/* ── Notifications page ── */
.notification-card-row,
.notification-card__row{
  display:flex!important;
  flex-direction:row!important;
  flex-wrap:nowrap!important;
  align-items:flex-start!important;
  width:100%!important;
  gap:14px!important;
}
.notification-list-card .notification-card__main{
  flex:1 1 auto!important;
  min-width:0!important;
}
.notification-list-card {
    background: rgba(10, 27, 54, 0.88) !important;
    border: 1px solid rgba(232, 184, 74, 0.18) !important;
    border-radius: 14px !important;
    box-shadow: 0 4px 14px rgba(0,0,0,.28) !important;
    transition: border-color .2s, background .2s;
}
.notification-list-card:hover {
    border-color: rgba(232, 184, 74, 0.45) !important;
    background: rgba(15, 35, 70, 0.95) !important;
}
.notification-list-card .notification-card__icon {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: rgba(232,184,74,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid rgba(232,184,74,0.22);
    box-sizing: border-box;
}
.notification-card__icon-img {
    width: 26px !important;
    height: 26px !important;
    object-fit: contain;
    display: block;
    filter: brightness(0) invert(1);
    opacity: 0.92;
}
.notification-card__headline{
    display:flex!important;
    justify-content:space-between!important;
    align-items:flex-start!important;
    gap:10px!important;
    width:100%!important;
}
.notification-card__headline .notification-card__date{
    margin-bottom:0!important;
}
.notification-card__date {
    font-size: 11px !important;
    font-weight: 700 !important;
    color: #e8b84a !important;
    letter-spacing: .02em;
    text-align:right!important;
    line-height:1.35!important;
}
.notification-card__title {
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #fff !important;
    margin-bottom: 0 !important;
    line-height: 1.35!important;
    flex:1 1 auto!important;
    min-width:0!important;
}
.notification-card__desc {
    font-size: 12px !important;
    color: rgba(255,255,255,.6) !important;
    line-height: 1.45;
    margin: 0 !important;
}

/* ── Top Winner List page (body.page-top-winner-list) ── */
body.page-top-winner-list .top-winners-page {
    max-width: 560px;
    margin: 0 auto;
    padding: 12px 4px 24px;
}
body.page-top-winner-list .top-winners-hero {
    padding: 10px 0 20px;
}
body.page-top-winner-list .top-winners-hero__icon-wrap {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: rgba(232,184,74,0.14);
    border: 2px solid rgba(232,184,74,0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
}
body.page-top-winner-list .top-winners-hero__icon {
    width: 38px !important;
    height: 38px !important;
    object-fit: contain;
    filter: brightness(0) saturate(100%) invert(76%) sepia(61%) saturate(415%) hue-rotate(352deg) brightness(87%) contrast(82%);
}
body.page-top-winner-list .top-winners-hero__title {
    font-size: 1.3rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: .03em;
    margin: 0 0 6px;
}
body.page-top-winner-list .top-winners-hero__sub {
    font-size: 13px;
    color: rgba(255,255,255,.5);
    margin: 0;
}

/* Winner Card */
body.page-top-winner-list .top-winner-card {
    background: rgba(10, 27, 54, 0.88) !important;
    border: 1px solid rgba(232, 184, 74, 0.2) !important;
    border-radius: 14px !important;
    box-shadow: 0 4px 14px rgba(0,0,0,.28) !important;
    transition: border-color .2s, background .2s;
    margin-bottom: 10px !important;
}
body.page-top-winner-list .top-winner-card:hover {
    border-color: rgba(232,184,74,0.5) !important;
    background: rgba(15, 35, 70, 0.95) !important;
}
body.page-top-winner-list .top-winner-card__inner {
    padding: 0px 10px 10px !important;
}

/* Row 1: Trophy icon — top center */
body.page-top-winner-list .top-winner-card__icon-row {
    display: flex;
    justify-content: center;
    margin-bottom: 2px;
    margin-top: -4px;
}
body.page-top-winner-list .top-winner-card__avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(232,184,74,0.15);
    border: 2px solid rgba(232,184,74,0.45);
    display: flex;
    align-items: center;
    justify-content: center;
}
body.page-top-winner-list .top-winner-card__trophy-ico {
    font-size: 22px !important;
    color: #e8b84a !important;
}

/* Row 2: Username & Digit — spaced row */
body.page-top-winner-list .top-winner-card__name-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    padding: 0 !important;
}
body.page-top-winner-list .top-winner-card__name {
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #fff !important;
    margin: 0 !important;
    letter-spacing: .02em;
    white-space: nowrap;
    display: flex;
    /* text-align: left; */
}

/* Row 3: Meta row — game name + date on top line, digit centered below */
body.page-top-winner-list .top-winner-card__meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    margin-bottom: 4px;
    flex-wrap: nowrap;
}
body.page-top-winner-list .top-winner-card__meta-left {
    display: flex;
    align-items: center;
    gap: 5px;
    flex: 1;
    min-width: 0;
}
body.page-top-winner-list .top-winner-card__meta-right {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
}

/* Icons (clock, gamepad) — yellow */
body.page-top-winner-list .top-winner-card__ico {
    color: #e8b84a !important;
    font-size: 11px !important;
    flex-shrink: 0;
}

/* Game name text — full, no truncation */
body.page-top-winner-list .top-winner-card__meta {
    font-size: 11px !important;
    color: rgba(255,255,255,.75) !important;
    white-space: normal;
    word-break: break-word;
    font-weight: 600;
    line-height: 1.3;
}

/* Digit badge — now inline in name row */
body.page-top-winner-list .top-winner-card__digit-badge {
    display: inline-block;
    background: rgba(232,184,74,0.15);
    border: 1px solid rgba(232,184,74,0.4);
    color: #e8b84a;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 20px;
    white-space: nowrap;
}

/* Date & Time */
body.page-top-winner-list .top-winner-card__time {
    font-size: 10px !important;
    color: rgba(255,255,255,.5) !important;
    font-weight: 500;
    white-space: nowrap;
}

/* Row 4: Prize block — center bottom */
body.page-top-winner-list .top-winner-card__prize-block {
    text-align: center;
    border-top: 1px solid rgba(232,184,74,0.15);
    padding-top: 10px;
}
body.page-top-winner-list .top-winner-card__prize-label {
    font-size: 10px !important;
    color: rgba(255,255,255,.4) !important;
    text-transform: uppercase;
    letter-spacing: .1em;
    display: block;
    margin-bottom: 2px;
}
body.page-top-winner-list .top-winner-card__prize-amt {
    font-size: 20px !important;
    font-weight: 800 !important;
    color: #e8b84a !important;
    line-height: 1.2;
    display: block;
    letter-spacing: .02em;
}
</style>
