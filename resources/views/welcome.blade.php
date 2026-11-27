<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>LintasGo – Sistem Reservasi & Operasional Travel Antar Kota</title>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&family=Bagel+Fat+One&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--bg:#FBF5EA;--card:rgba(255,255,255,.76);--ink:#2A1418;--mut:#6B5A5E;--acc:#7A1F2B;--btn:#7A1F2B;--on:#FBF5EA;--blu:#2A5DA8;--bluT:#2A5DA8;--blul:#DCE7F6;--ln:rgba(122,31,43,.2);--g1:rgba(122,31,43,.14);--g2:rgba(42,93,168,.17);box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)}
@media(prefers-color-scheme:dark){:root:not([data-theme="light"]){--bg:#1B0E12;--card:rgba(58,28,35,.74);--ink:#F7EBDD;--mut:#CDB8B2;--acc:#F0A3AC;--btn:#8E2B3A;--blu:#3F7AD6;--bluT:#9CC0F5;--blul:#1E3558;--ln:rgba(240,163,172,.25);--g1:rgba(142,43,58,.35);--g2:rgba(63,122,214,.25)}}
:root[data-theme="dark"]{--bg:#1B0E12;--card:rgba(58,28,35,.74);--ink:#F7EBDD;--mut:#CDB8B2;--acc:#F0A3AC;--btn:#8E2B3A;--blu:#3F7AD6;--bluT:#9CC0F5;--blul:#1E3558;--ln:rgba(240,163,172,.25);--g1:rgba(142,43,58,.35);--g2:rgba(63,122,214,.25)}
html{scroll-padding-top:env(safe-area-inset-top,0px);scroll-behavior:smooth}
*{box-sizing:border-box}
body{margin:0;min-height:100vh;color:var(--ink);background:var(--bg) radial-gradient(60rem 40rem at -10% -10%,var(--g1),transparent 60%) fixed,radial-gradient(50rem 36rem at 110% 25%,var(--g2),transparent 60%) fixed,radial-gradient(44rem 30rem at 35% 115%,var(--g1),transparent 60%) fixed;font:16px/1.6 'Plus Jakarta Sans',system-ui,sans-serif}
body::before{content:"";position:fixed;inset:0;z-index:-1;pointer-events:none;opacity:.6;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 800' preserveAspectRatio='none'%3E%3Cpath d='M-20 640C200 520 330 760 560 600S900 220 1220 320' fill='none' stroke='%237A1F2B' stroke-opacity='.22' stroke-width='3' stroke-dasharray='4 14' stroke-linecap='round'/%3E%3Ccircle cx='560' cy='600' r='9' fill='%232A5DA8' fill-opacity='.4'/%3E%3C/svg%3E") center/cover no-repeat}
h1,h2,h3{font-family:'Bricolage Grotesque',system-ui,sans-serif;line-height:1.1;margin:0}
a{color:var(--bluT)}
.wrap{max-width:1120px;margin:0 auto;padding:0 20px}
.mut{color:var(--mut)}.small{font-size:13px}
.top{position:sticky;top:0;z-index:5;backdrop-filter:blur(14px);background:color-mix(in srgb,var(--bg) 78%,transparent);border-bottom:1px solid var(--ln)}
.bar{display:flex;align-items:center;gap:18px;min-height:64px;flex-wrap:wrap}
.logo{font:700 24px 'Bricolage Grotesque',sans-serif;color:var(--acc);text-decoration:none;margin-right:auto}
nav{display:flex;gap:6px;align-items:center}
nav a,.lnk{color:var(--ink);text-decoration:none;font:600 14px 'Plus Jakarta Sans',sans-serif;padding:8px 12px;border-radius:999px;background:none;border:0;cursor:pointer}
nav a:hover,.lnk:hover{background:var(--g1)}
.btn{font:700 15px 'Plus Jakarta Sans',sans-serif;min-height:44px;padding:0 20px;border-radius:999px;border:2px solid var(--btn);background:var(--btn);color:var(--on);cursor:pointer;transition:transform .15s,box-shadow .15s}
.btn:hover:not(:disabled){transform:translateY(-1px);box-shadow:0 8px 20px -8px var(--btn)}
.btn.alt{background:transparent;color:var(--acc);border-color:var(--ln)}
.btn.w{width:100%;margin-top:10px}
.btn:disabled{opacity:.45;cursor:not-allowed}
:focus-visible{outline:3px solid var(--blu);outline-offset:2px}
.pill{display:inline-flex;gap:6px;align-items:center;border-radius:999px;padding:4px 12px;font-size:12px;font-weight:700;background:var(--blul);color:var(--bluT)}
.card{background:var(--card);backdrop-filter:blur(12px);border:1px solid var(--ln);border-radius:22px;padding:22px;box-shadow:0 18px 40px -26px rgba(79,17,28,.55)}
.hero{margin-top:22px;border-radius:30px;padding:clamp(28px,6vw,64px);padding-bottom:clamp(64px,9vw,96px);color:#FBF5EA;position:relative;overflow:hidden;background:linear-gradient(135deg,#4F111C,#7A1F2B 60%,#963040)}
.hero::before,.hero::after{content:"";position:absolute;border-radius:50%;background:#2A5DA8;opacity:.4}
.hero::before{width:380px;height:380px;right:-90px;top:-130px}
.hero::after{width:200px;height:200px;right:150px;bottom:-110px;opacity:.28;background:#FBF5EA}
.hero>*{position:relative;z-index:1}
.hero h1{font-size:clamp(34px,6vw,60px);max-width:14ch}
.hero p{max-width:46ch;opacity:.92}
.search{margin-top:-52px;position:relative;z-index:2;display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;align-items:end;margin-left:clamp(0px,3vw,32px);margin-right:clamp(0px,3vw,32px)}
.f{display:block;font-size:13px;font-weight:600;color:var(--mut)}
.f input,.f select{display:block;width:100%;min-height:46px;margin-top:4px;padding:0 14px;border-radius:14px;border:1.5px solid var(--ln);background:var(--bg);color:var(--ink);font:500 15px 'Plus Jakarta Sans',sans-serif}
.guest{margin:18px 0;display:flex;gap:14px;align-items:center;flex-wrap:wrap;padding:14px 18px;border-radius:18px;background:var(--blul);color:var(--bluT);font-size:14px}
.guest b{color:var(--ink)}
.trip{display:grid;grid-template-columns:auto 1fr auto;gap:18px;align-items:center;margin-top:14px}
.tm{font:700 26px 'Bricolage Grotesque',sans-serif;color:var(--acc);line-height:1}
.tm small{display:block;font:500 12px 'Plus Jakarta Sans';color:var(--mut);margin-top:4px}
.pr{text-align:right}.pr strong{font:700 20px 'Bricolage Grotesque'}
@media(max-width:640px){.trip{grid-template-columns:1fr}.pr{text-align:left}}
.sv{display:grid;grid-template-columns:1.35fr 1fr;gap:18px;margin-top:22px}
@media(max-width:860px){.sv{grid-template-columns:1fr}}
#stage{height:min(460px,70vw);min-height:300px;border-radius:18px;background:radial-gradient(circle at 50% 30%,rgba(255,255,255,.55),transparent 70%),linear-gradient(180deg,var(--blul),transparent);cursor:grab;touch-action:pan-y;position:relative}
#stage canvas{width:100%;height:100%;display:block;border-radius:18px}
.legend{display:flex;gap:14px;flex-wrap:wrap;font-size:13px;color:var(--mut);margin-top:10px}
.legend i{display:inline-block;width:14px;height:14px;border-radius:5px;margin-right:6px;vertical-align:-2px;border:1.5px solid var(--ln)}
.seats{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;max-width:240px;margin:14px 0}
.seat,.drv{min-height:48px;border-radius:12px;border:2px solid var(--ln);font:700 15px 'Plus Jakarta Sans';background:#FFF4DC;color:#2A1418;cursor:pointer}
.seat[aria-pressed="true"]{background:var(--blu);border-color:var(--blu);color:#fff}
.seat:disabled{background:#8E7F82;color:#fff;border-style:dashed;cursor:not-allowed}
.drv{display:grid;place-items:center;font-size:11px;background:transparent;color:var(--mut);border-style:dotted}
.err{color:#B3261E;font-size:13px;min-height:1.2em;margin:6px 0 0}
:root[data-theme="dark"] .err{color:#FFB4AB}
.row{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px;margin:10px 0}
.opt{display:flex;gap:10px;align-items:center;padding:12px 14px;border:2px solid var(--ln);border-radius:14px;cursor:pointer;font-weight:600}
.opt:has(input:checked){border-color:var(--blu);background:var(--blul)}
.badge{font-weight:700;font-size:13px;padding:5px 12px;border-radius:999px;background:#FFF0CC;color:#7A4A06}
.badge.b2{background:var(--blul);color:var(--bluT)}.badge.b3{background:#DDF3E6;color:#0F5E3A}
.tk{display:flex;border-radius:18px;overflow:hidden;border:2px solid var(--acc);margin-top:12px;background:var(--bg)}
.tk .l{background:var(--acc);color:#FBF5EA;padding:14px 18px;min-width:96px}
.tk .l b{font:700 22px 'Bricolage Grotesque'}
.tk .m{padding:14px 18px;flex:1;border-left:2px dashed var(--ln)}
.qr{display:grid;grid-template-columns:repeat(11,5px);gap:1px;padding:8px;background:#fff;align-self:center;margin:10px}
.qr i{width:5px;height:5px;background:#2A1418}.qr i.o{background:#fff}
dialog{border:0;padding:0;border-radius:26px;width:min(440px,92vw);color:var(--ink);background:var(--bg);box-shadow:0 40px 90px -30px rgba(0,0,0,.6)}
dialog::backdrop{background:rgba(42,20,24,.55);backdrop-filter:blur(5px)}
.gbox{padding:28px;position:relative}
.x{position:absolute;right:14px;top:12px;width:44px;height:44px;border:0;background:none;font-size:26px;color:var(--mut);cursor:pointer}
.tabs{display:flex;gap:6px;margin:14px 0;background:var(--g1);padding:4px;border-radius:999px}
.tabs button{flex:1;min-height:40px;border:0;border-radius:999px;background:none;font:700 14px 'Plus Jakarta Sans';color:var(--ink);cursor:pointer}
.tabs button[aria-selected="true"]{background:var(--btn);color:var(--on)}
#gf.login .d{display:none}
#gf .f{margin-top:10px}
#toast{position:fixed;left:50%;bottom:calc(20px + env(safe-area-inset-bottom,0px));transform:translateX(-50%);background:#2A1418;color:#FBF5EA;padding:12px 20px;border-radius:999px;font-weight:600;font-size:14px;opacity:0;pointer-events:none;transition:opacity .25s;z-index:20}
#toast.on{opacity:1}
[hidden]{display:none!important}
section{margin-top:22px}
footer{margin:48px 0 28px;font-size:13px;color:var(--mut)}
@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}*{transition:none!important}}
:root{--hard:#4F111C;--tk:#FFE9B8;--sk:#C4DBF6}
@media(prefers-color-scheme:dark){:root:not([data-theme="light"]){--hard:#F0A3AC;--tk:#4A2A32}}
:root[data-theme="dark"]{--hard:#F0A3AC;--tk:#4A2A32}
body{overflow-x:hidden}
h1,h2,h3,.logo,.tm,.pr strong{font-family:'Bagel Fat One','Bricolage Grotesque',system-ui,sans-serif;font-weight:400;letter-spacing:.2px}
body::after{content:"";position:fixed;inset:0;z-index:-1;pointer-events:none;opacity:.55;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220'%3E%3Cpath d='M40 30l4 11 11 4-11 4-4 11-4-11-11-4 11-4z' fill='%237A1F2B' fill-opacity='.22'/%3E%3Cpath d='M160 120l3 8 8 3-8 3-3 8-3-8-8-3 8-3z' fill='%232A5DA8' fill-opacity='.3'/%3E%3Ccircle cx='120' cy='40' r='4' fill='%232A5DA8' fill-opacity='.26'/%3E%3Ccircle cx='60' cy='170' r='5' fill='%237A1F2B' fill-opacity='.2'/%3E%3C/svg%3E") 0 0/220px}
.top{border-bottom:3px solid var(--hard)}
.logo{font-size:30px;text-shadow:2px 2px 0 var(--sk)}
nav a,.lnk{border:2px solid transparent}
nav a:hover,.lnk:hover{border-color:var(--hard);background:var(--tk)}
.card{background:color-mix(in srgb,var(--bg) 82%,#fff);backdrop-filter:none;border:3px solid var(--hard);border-radius:26px;box-shadow:6px 6px 0 var(--hard)}
.btn{border:3px solid var(--hard);box-shadow:4px 4px 0 var(--hard);font-weight:800}
.btn:hover:not(:disabled){transform:translate(-1px,-1px);box-shadow:5px 5px 0 var(--hard)}
.btn:active:not(:disabled){transform:translate(3px,3px);box-shadow:1px 1px 0 var(--hard)}
.btn.alt{background:var(--bg);color:var(--ink)}
.pill{border:2px solid var(--hard);background:var(--tk);color:var(--ink);transform:rotate(-2deg);animation:wob 4s ease-in-out infinite}
@keyframes wob{50%{transform:rotate(2deg)}}
.hero{border:3px solid var(--hard);box-shadow:8px 8px 0 var(--hard);border-radius:38px}
.hero h1{font-size:clamp(42px,7.5vw,80px);line-height:1;text-shadow:3px 3px 0 #2A5DA8;max-width:12ch}
.search{background:var(--tk);border:3px solid var(--hard);border-radius:28px;box-shadow:6px 6px 0 var(--hard)}
.search::before{content:"TIKET ✦ LINTASGO";position:absolute;top:-17px;left:24px;background:var(--hard);color:var(--bg);padding:3px 14px;border-radius:10px;font:800 12px 'Plus Jakarta Sans',sans-serif;letter-spacing:.08em;transform:rotate(-2deg)}
.f input,.f select{border:2.5px solid var(--hard);background:var(--bg);border-radius:16px}
.guest{border:3px dashed var(--hard);border-radius:22px}
.trip{transition:transform .18s}
.trip:hover{transform:rotate(-.5deg) translateY(-3px)}
.badge{border:2px solid var(--hard)}
.seat,.drv{border:2.5px solid var(--hard);border-radius:14px}
dialog{border:3px solid var(--hard);box-shadow:8px 8px 0 var(--hard)}
#toast{border:3px solid var(--hard);background:var(--tk);color:var(--ink)}
.mq{margin:34px -20px 8px;border-block:3px solid var(--hard);background:var(--hard);color:var(--bg);overflow:hidden;transform:rotate(-1.2deg)}
.mq>div{display:flex;width:max-content;animation:mq 30s linear infinite;font:400 22px 'Bagel Fat One',sans-serif;padding:10px 0}
.mq span{white-space:nowrap}
@keyframes mq{to{transform:translateX(-50%)}}
.sh{display:flex;align-items:end;justify-content:space-between;gap:6px 18px;margin:54px 0 16px;flex-wrap:wrap}
.sh h2{font-size:clamp(30px,5vw,46px)}
.sh p{margin:0;max-width:40ch}
.slots{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:18px}
.slot{text-align:left;border:3px solid var(--hard);border-radius:26px;box-shadow:6px 6px 0 var(--hard);padding:18px;cursor:pointer;color:var(--ink);font:inherit;transition:transform .15s,box-shadow .15s;background:color-mix(in srgb,var(--ac) 58%,var(--bg))}
.slot:hover{transform:translate(-2px,-2px) rotate(-.8deg);box-shadow:8px 8px 0 var(--hard)}
.slot[aria-pressed="true"]{outline:4px solid var(--blu);outline-offset:3px}
.slot b{display:block;font:400 30px/1 'Bagel Fat One',sans-serif;margin:8px 0 4px}
.slot em{font-style:normal;font-size:13px;font-weight:700}
.slot:nth-child(1){--ac:#F6C3CB}.slot:nth-child(2){--ac:#FFD98A}.slot:nth-child(3){--ac:#9EC3F0}.slot:nth-child(4){--ac:#D98A94}.slot:nth-child(5){--ac:#7FA7DE}.slot:nth-child(6){--ac:#E7D2AC}
.map{position:relative;aspect-ratio:16/9;min-height:300px;border:3px solid var(--hard);border-radius:30px;box-shadow:6px 6px 0 var(--hard);background:color-mix(in srgb,var(--sk) 60%,var(--bg));overflow:hidden}
.map svg{position:absolute;inset:0;width:100%;height:100%}
.pin{position:absolute;transform:translate(-50%,-100%);display:flex;flex-direction:column;align-items:center;gap:6px;background:none;border:0;cursor:pointer;font:inherit;color:var(--ink);padding:4px}
.pin .tg{background:var(--bg);border:2.5px solid var(--hard);border-radius:999px;padding:3px 12px;font-weight:800;font-size:13px;box-shadow:3px 3px 0 var(--hard);white-space:nowrap}
.pin .dt{width:22px;height:22px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);background:var(--btn);border:3px solid var(--hard);transition:transform .2s}
.pin:hover .dt,.pin:focus-visible .dt{transform:rotate(-45deg) scale(1.3)}
.steps{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:18px}
.stp b{display:grid;place-items:center;width:46px;height:46px;border-radius:50%;background:var(--btn);color:var(--on);border:3px solid var(--hard);font:400 22px 'Bagel Fat One',sans-serif;transform:rotate(-6deg)}
.stp h3{font-size:22px;margin:12px 0 4px}
.stp p{margin:0}
footer{text-align:center}
@media(prefers-reduced-motion:reduce){.mq>div,.pill{animation:none}}

body::before,body::after{display:none}
#scene{position:fixed;inset:0;z-index:-2;pointer-events:none;overflow:hidden}
#scene svg{width:100%;height:100%;display:block}
#scene::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,color-mix(in srgb,var(--bg) 14%,transparent),color-mix(in srgb,var(--bg) 58%,transparent) 62%,color-mix(in srgb,var(--bg) 84%,transparent))}
@media(prefers-color-scheme:dark){:root:not([data-theme="light"]) #scene svg{filter:brightness(.5) saturate(.85)}}
:root[data-theme="dark"] #scene svg{filter:brightness(.5) saturate(.85)}
nav a[aria-current="page"]{background:var(--tk);border-color:var(--hard)}
.view{animation:vin .35s ease}
@keyframes vin{from{opacity:0;transform:translateY(8px)}}
.view>.sh:first-child{margin-top:26px}
.rs{display:flex;gap:16px;align-items:center;flex-wrap:wrap;margin-bottom:18px}
.rs b{font:400 58px/1 'Bagel Fat One',sans-serif;color:var(--acc)}
.rgrid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:18px}
.rc{transition:transform .18s}
.rc:hover{transform:rotate(.6deg) translateY(-3px)}
.rc .st{color:#B86E00;letter-spacing:2px;font-size:18px}
.rc p{margin:6px 0 10px}
@media(prefers-reduced-motion:reduce){.view{animation:none}}
.badge.b4{background:#ECE3E3;color:#5B3A3F}
button.pill{font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer}
.tl{display:flex;gap:8px;list-style:none;padding:0;margin:14px 0;flex-wrap:wrap}
.tl li{flex:1;min-width:120px;padding:8px 12px;border:2.5px dashed var(--ln);border-radius:14px;font-weight:700;font-size:13px;color:var(--mut)}
.tl li.on{border:2.5px solid var(--hard);background:var(--tk);color:var(--ink)}
.demo{margin-top:18px;padding:14px;border:2.5px dashed var(--hard);border-radius:18px;display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.qrs{width:176px;height:176px;display:block;border:3px solid var(--hard);border-radius:12px;background:#fff}
.etk{padding:0;overflow:hidden}
.etk .eh{display:flex;justify-content:space-between;align-items:center;padding:14px 22px;background:var(--btn);color:var(--on);font:400 26px 'Bagel Fat One',sans-serif}
.etk .eb{display:grid;grid-template-columns:1fr auto;gap:22px;padding:22px;align-items:center}
.etk dl{display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:12px 16px;margin:14px 0 0}
.etk dt{font-size:11px;color:var(--mut);font-weight:700;letter-spacing:.05em;text-transform:uppercase}
.etk dd{margin:0;font-weight:700}
@media(max-width:640px){.etk .eb{grid-template-columns:1fr}}
@media print{.top,footer,#scene,.mq,.noprint,#toast,.guest{display:none!important}body{background:#fff}.card{box-shadow:none}}
</style>
</head>
<body>
<div id="scene" aria-hidden="true"><svg viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice"><defs><linearGradient id="sk" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#8FB6E8"/><stop offset=".55" stop-color="#F8DCCB"/><stop offset="1" stop-color="#FFE9B8"/></linearGradient><radialGradient id="sn"><stop offset="0" stop-color="#FFF3CF"/><stop offset="1" stop-color="#FFF3CF" stop-opacity="0"/></radialGradient></defs><rect width="1600" height="900" fill="url(#sk)"/><circle cx="1130" cy="400" r="260" fill="url(#sn)"/><circle cx="1130" cy="400" r="72" fill="#FFF6DA"/><g fill="#fff" opacity=".75"><ellipse cx="260" cy="170" rx="130" ry="26"/><ellipse cx="340" cy="150" rx="80" ry="24"/><ellipse cx="1240" cy="120" rx="150" ry="28"/><ellipse cx="760" cy="230" rx="110" ry="20"/></g><path d="M0 560L220 470 400 540 640 380 740 330 820 360 990 520 1200 440 1400 520 1600 470V900H0Z" fill="#7FA7DE" opacity=".6"/><path d="M0 640Q260 540 560 620T1100 590 1600 630V900H0Z" fill="#5F86C0" opacity=".55"/><path d="M0 700Q300 610 620 680T1240 650 1600 690V900H0Z" fill="#A9B98A"/><g fill="none" stroke="#8FA06F" stroke-width="3" opacity=".7"><path d="M0 730Q320 650 640 715T1280 685 1600 722"/><path d="M0 765Q330 690 650 750T1290 722 1600 758"/></g><path d="M0 800Q300 740 700 790T1600 770V900H0Z" fill="#8EA36E"/><path d="M-60 900C380 800 700 840 1000 760S1500 700 1680 690V740C1450 760 1150 840 820 900Z" fill="#6B3A44"/><path d="M-20 880C400 800 720 835 1010 760S1480 715 1660 712" fill="none" stroke="#FBE9C8" stroke-width="5" stroke-dasharray="26 30" stroke-linecap="round"/><g stroke="#3F5A3A" stroke-width="7" fill="none" stroke-linecap="round"><path d="M180 790V690"/><path d="M180 690Q140 660 100 670M180 690Q170 640 140 625M180 690Q215 650 255 655M180 690Q225 690 245 715"/><path d="M1450 770V690"/><path d="M1450 690Q1410 665 1375 676M1450 690Q1440 645 1410 632M1450 690Q1485 655 1520 662"/></g></svg></div>
<header class="top"><div class="wrap bar">
<a class="logo" href="#home">LintasGo</a>
<nav aria-label="Menu utama">
<a href="#home" data-v="home">Beranda</a>
<a href="#jadwal" data-v="jadwal">Jadwal</a>
<a href="#peta" data-v="peta">Peta</a>
@auth
<a href="{{ route('pemesanan.index') }}" class="lnk" data-v="pesanan">Pesanan saya</a>
@if(Auth::user()->isAdmin())
<a href="{{ route('admin.dashboard') }}" class="lnk">Admin</a>
@endif
@else
<button class="lnk" id="navOrd" type="button">Pesanan saya</button>
@endauth
</nav>
<div id="auth" style="display:flex;gap:8px;align-items:center">
@auth
<a href="{{ route('profil.show') }}" class="pill" title="Profil saya" style="text-decoration:none">Halo, {{ Auth::user()->name }}</a>
@if(Auth::user()->isAdmin())
<a href="{{ route('admin.dashboard') }}" class="btn alt" style="text-decoration:none">Panel Admin</a>
@endif
<form method="POST" action="{{ route('logout') }}" style="display:inline;margin:0">
@csrf
<button class="btn alt" type="submit">Keluar</button>
</form>
@else
<button class="btn alt" type="button" onclick="openGate('login')">Masuk</button>
<button class="btn" type="button" onclick="openGate('daftar')">Daftar</button>
@endauth
</div>
</div></header>

<main class="wrap">
<div class="view" id="v-home">
<section class="hero">
<span class="pill">Travel antarkota Jawa Timur</span>
<h1 style="margin:14px 0">Pilih kursimu, berangkat tanpa antre.</h1>
<p>Cari jadwal Jember, Surabaya, Malang, dan Banyuwangi. Lihat kursi kosong dalam tampilan 3D sebelum memutuskan.</p>
</section>
<form class="card search" id="cari" aria-label="Cari jadwal" novalidate>
<label class="f">Asal
<select id="qf">
<option value="">Semua kota</option>
@if(isset($origins) && count($origins) > 0)
@foreach($origins as $origin)
<option value="{{ $origin }}">{{ $origin }}</option>
@endforeach
@endif
</select>
</label>
<label class="f">Tujuan
<select id="qt">
<option value="">Semua kota</option>
@if(isset($destinations) && count($destinations) > 0)
@foreach($destinations as $destination)
<option value="{{ $destination }}">{{ $destination }}</option>
@endforeach
@endif
</select>
</label>
<label class="f">Tanggal<input type="date" id="qd"></label>
<button class="btn" type="submit">Cari jadwal</button>
</form>
<div class="mq" aria-hidden="true"><div id="mqi"></div></div>
<div class="guest" id="guestNote"><span><b>Mode tamu.</b> Kamu bisa mencari jadwal, melihat harga, dan memeriksa kursi kosong. Memesan, membayar, dan e-ticket memerlukan akun.</span><button class="btn alt" id="gn1" type="button">Masuk atau daftar</button></div>
<section id="ulasan"><div class="sh"><h2>Kata penumpang ♡</h2><p class="mut">Ulasan contoh untuk tampilan. Nanti diganti ulasan sungguhan dari penumpang yang perjalanannya sudah selesai.</p></div>
<div class="rs"><b>4,8</b><span><span class="st" style="color:#B86E00;font-size:22px;letter-spacing:2px" aria-label="4,8 dari 5">★★★★★</span><br><span class="mut small">rata-rata ulasan (contoh)</span></span><button class="btn" id="rvBtn" type="button">Tulis ulasan</button></div>
<div class="rgrid" id="revs"></div></section>
<section id="cara"><div class="sh"><h2>Cara pesan, gampang banget</h2></div><div class="steps">
<div class="card stp"><b>1</b><h3>Cari jadwal</h3><p class="mut">Tanpa akun. Pilih kota, tanggal, dan jam berangkat.</p></div>
<div class="card stp"><b>2</b><h3>Pilih kursi 3D</h3><p class="mut">Putar van, lalu klik kursi favoritmu (maksimal 3).</p></div>
<div class="card stp"><b>3</b><h3>Masuk atau daftar</h3><p class="mut">Akun dibutuhkan untuk memesan dan membayar.</p></div>
<div class="card stp"><b>4</b><h3>Bayar, lalu e-ticket</h3><p class="mut">Unggah bukti QRIS atau transfer. Tiket QR terbit setelah diverifikasi.</p></div>
</div></section>
</div>
<div class="view" id="v-jadwal" hidden>
<section id="jadwal"><div class="sh"><h2>Jadwal sepanjang hari ✦</h2><p class="mut">Enam waktu berangkat untuk 24 jam penuh. Ketuk satu untuk menyaring jadwal.</p></div><div class="slots" id="slots"></div></section>
<section id="trips" aria-live="polite" aria-label="Hasil pencarian"></section>
</div>
<div class="view" id="v-peta" hidden>
<section id="peta"><div class="sh"><h2>Peta rute</h2><p class="mut">Peta ilustrasi, bukan skala. Ketuk kota untuk melihat keberangkatannya.</p></div>
<div class="map" id="map"><svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"><path d="M8 40C12 20 30 14 46 18S74 10 88 28 98 62 92 84 62 94 40 88 6 70 8 40Z" style="fill:var(--bg);stroke:var(--hard);stroke-width:3" vector-effect="non-scaling-stroke"/><g fill="none" style="stroke:var(--btn);stroke-width:3;stroke-dasharray:2 7;stroke-linecap:round"><path d="M66 68Q50 46 58 26" vector-effect="non-scaling-stroke"/><path d="M66 68Q58 62 50 50" vector-effect="non-scaling-stroke"/><path d="M66 68Q78 84 88 80" vector-effect="non-scaling-stroke"/></g></svg></div></section>
</div>
<section id="seatView" class="view" hidden>
<div class="sv">
<div class="card">
<h2 style="font-size:24px;margin-bottom:10px">Pilih kursi</h2>
<div id="stage" aria-label="Denah kursi 3D. Gunakan tombol kursi di samping sebagai alternatif." role="group"></div>
<div class="legend"><span><i style="background:#FFF4DC"></i>Tersedia</span><span><i style="background:var(--blu)"></i>Dipilih</span><span><i style="background:#8E7F82"></i>Terisi</span><span>Seret untuk memutar, klik kursi untuk memilih</span></div>
</div>
<div class="card">
<h2 id="tTitle" style="font-size:26px"></h2><p class="mut" id="tMeta" style="margin:4px 0 0"></p>
<div class="seats" id="seatBtns" role="group" aria-label="Pilih kursi (maksimal 3)"></div>
<p id="picked" class="mut" style="margin:0 0 6px">Belum ada kursi dipilih</p>
<p style="margin:0;font-size:18px">Total <strong id="total" style="font-family:'Bricolage Grotesque'">Rp 0</strong></p>
<button class="btn w" id="goBook" type="button" disabled>Lanjut pesan</button>
<p class="mut small" id="gateHint" style="margin:10px 0 0"></p>
</div>
</div>
</section>
<section id="bookView" class="view" hidden></section>
<section id="ordersView" class="view" hidden></section>
<section id="orderView" class="view" hidden></section>
<section id="ticketView" class="view" hidden></section>
<section id="profView" class="view" hidden></section>
<footer>LintasGo &copy; {{ date('Y') }} &ndash; Sistem Informasi Pemesanan & Pengelolaan Operasional Travel Antar Kota Jawa Timur.</footer>
</main>

<dialog id="gate" aria-labelledby="gt"><div class="gbox">
<button class="x" id="gx" type="button" aria-label="Tutup">×</button>
<h2 id="gt" style="font-size:28px">Masuk untuk memesan</h2>
<p class="mut small" style="margin:6px 40px 0 0">Mencari jadwal dan melihat kursi tidak perlu akun. Memesan, membayar, dan e-ticket perlu akun.</p>
<div class="tabs" role="tablist"><button role="tab" id="tl" type="button" aria-selected="true">Masuk</button><button role="tab" id="td" type="button" aria-selected="false">Daftar</button></div>
<form id="gf" class="login" method="POST" action="{{ route('login.attempt') }}" novalidate>
@csrf
<label class="f d">Nama lengkap<input id="gn" name="name" autocomplete="name" maxlength="100"></label>
<label class="f">Email<input id="ge" name="email" type="email" autocomplete="email" maxlength="150" required></label>
<label class="f d">Nomor telepon<input id="gph" name="phone" type="tel" autocomplete="tel" maxlength="20" placeholder="08xxxxxxxxxx"></label>
<label class="f">Kata sandi (minimal 8 karakter)<input id="gpw" name="password" type="password" autocomplete="current-password" maxlength="72" required></label>
<p class="err" id="gerr" role="alert"></p>
<button class="btn w" type="submit" id="gs">Masuk</button>
<div style="text-align:center;margin-top:10px">
<a href="{{ route('login') }}" class="mut small" style="text-decoration:underline">Halaman login penuh</a>
<span class="mut small"> · </span>
<a href="{{ route('register') }}" class="mut small" style="text-decoration:underline">Daftar akun</a>
</div>
</form>
</div></dialog>
<div id="toast" role="status" aria-live="polite"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>/* qrcode-generator 1.4.4 (MIT) */
//---------------------------------------------------------------------
//
// QR Code Generator for JavaScript
//
// Copyright (c) 2009 Kazuhiko Arase
//
// URL: http://www.d-project.com/
//
// Licensed under the MIT license:
//  http://www.opensource.org/licenses/mit-license.php
//
// The word 'QR Code' is registered trademark of
// DENSO WAVE INCORPORATED
//  http://www.denso-wave.com/qrcode/faqpatent-e.html
//
//---------------------------------------------------------------------

var qrcode = function() {

  //---------------------------------------------------------------------
  // qrcode
  //---------------------------------------------------------------------

  /**
   * qrcode
   * @param typeNumber 1 to 40
   * @param errorCorrectionLevel 'L','M','Q','H'
   */
  var qrcode = function(typeNumber, errorCorrectionLevel) {

    var PAD0 = 0xEC;
    var PAD1 = 0x11;

    var _typeNumber = typeNumber;
    var _errorCorrectionLevel = QRErrorCorrectionLevel[errorCorrectionLevel];
    var _modules = null;
    var _moduleCount = 0;
    var _dataCache = null;
    var _dataList = [];

    var _this = {};

    var makeImpl = function(test, maskPattern) {

      _moduleCount = _typeNumber * 4 + 17;
      _modules = function(moduleCount) {
        var modules = new Array(moduleCount);
        for (var row = 0; row < moduleCount; row += 1) {
          modules[row] = new Array(moduleCount);
          for (var col = 0; col < moduleCount; col += 1) {
            modules[row][col] = null;
          }
        }
        return modules;
      }(_moduleCount);

      setupPositionProbePattern(0, 0);
      setupPositionProbePattern(_moduleCount - 7, 0);
      setupPositionProbePattern(0, _moduleCount - 7);
      setupPositionAdjustPattern();
      setupTimingPattern();
      setupTypeInfo(test, maskPattern);

      if (_typeNumber >= 7) {
        setupTypeNumber(test);
      }

      if (_dataCache == null) {
        _dataCache = createData(_typeNumber, _errorCorrectionLevel, _dataList);
      }

      mapData(_dataCache, maskPattern);
    };

    var setupPositionProbePattern = function(row, col) {

      for (var r = -1; r <= 7; r += 1) {

        if (row + r <= -1 || _moduleCount <= row + r) continue;

        for (var c = -1; c <= 7; c += 1) {

          if (col + c <= -1 || _moduleCount <= col + c) continue;

          if ( (0 <= r && r <= 6 && (c == 0 || c == 6) )
              || (0 <= c && c <= 6 && (r == 0 || r == 6) )
              || (2 <= r && r <= 4 && 2 <= c && c <= 4) ) {
            _modules[row + r][col + c] = true;
          } else {
            _modules[row + r][col + c] = false;
          }
        }
      }
    };

    var getBestMaskPattern = function() {

      var minLostPoint = 0;
      var pattern = 0;

      for (var i = 0; i < 8; i += 1) {

        makeImpl(true, i);

        var lostPoint = QRUtil.getLostPoint(_this);

        if (i == 0 || minLostPoint > lostPoint) {
          minLostPoint = lostPoint;
          pattern = i;
        }
      }

      return pattern;
    };

    var setupTimingPattern = function() {

      for (var r = 8; r < _moduleCount - 8; r += 1) {
        if (_modules[r][6] != null) {
          continue;
        }
        _modules[r][6] = (r % 2 == 0);
      }

      for (var c = 8; c < _moduleCount - 8; c += 1) {
        if (_modules[6][c] != null) {
          continue;
        }
        _modules[6][c] = (c % 2 == 0);
      }
    };

    var setupPositionAdjustPattern = function() {

      var pos = QRUtil.getPatternPosition(_typeNumber);

      for (var i = 0; i < pos.length; i += 1) {

        for (var j = 0; j < pos.length; j += 1) {

          var row = pos[i];
          var col = pos[j];

          if (_modules[row][col] != null) {
            continue;
          }

          for (var r = -2; r <= 2; r += 1) {

            for (var c = -2; c <= 2; c += 1) {

              if (r == -2 || r == 2 || c == -2 || c == 2
                  || (r == 0 && c == 0) ) {
                _modules[row + r][col + c] = true;
              } else {
                _modules[row + r][col + c] = false;
              }
            }
          }
        }
      }
    };

    var setupTypeNumber = function(test) {

      var bits = QRUtil.getBCHTypeNumber(_typeNumber);

      for (var i = 0; i < 18; i += 1) {
        var mod = (!test && ( (bits >> i) & 1) == 1);
        _modules[Math.floor(i / 3)][i % 3 + _moduleCount - 8 - 3] = mod;
      }

      for (var i = 0; i < 18; i += 1) {
        var mod = (!test && ( (bits >> i) & 1) == 1);
        _modules[i % 3 + _moduleCount - 8 - 3][Math.floor(i / 3)] = mod;
      }
    };

    var setupTypeInfo = function(test, maskPattern) {

      var data = (_errorCorrectionLevel << 3) | maskPattern;
      var bits = QRUtil.getBCHTypeInfo(data);

      // vertical
      for (var i = 0; i < 15; i += 1) {

        var mod = (!test && ( (bits >> i) & 1) == 1);

        if (i < 6) {
          _modules[i][8] = mod;
        } else if (i < 8) {
          _modules[i + 1][8] = mod;
        } else {
          _modules[_moduleCount - 15 + i][8] = mod;
        }
      }

      // horizontal
      for (var i = 0; i < 15; i += 1) {

        var mod = (!test && ( (bits >> i) & 1) == 1);

        if (i < 8) {
          _modules[8][_moduleCount - i - 1] = mod;
        } else if (i < 9) {
          _modules[8][15 - i - 1 + 1] = mod;
        } else {
          _modules[8][15 - i - 1] = mod;
        }
      }

      // fixed module
      _modules[_moduleCount - 8][8] = (!test);
    };

    var mapData = function(data, maskPattern) {

      var inc = -1;
      var row = _moduleCount - 1;
      var bitIndex = 7;
      var byteIndex = 0;
      var maskFunc = QRUtil.getMaskFunction(maskPattern);

      for (var col = _moduleCount - 1; col > 0; col -= 2) {

        if (col == 6) col -= 1;

        while (true) {

          for (var c = 0; c < 2; c += 1) {

            if (_modules[row][col - c] == null) {

              var dark = false;

              if (byteIndex < data.length) {
                dark = ( ( (data[byteIndex] >>> bitIndex) & 1) == 1);
              }

              var mask = maskFunc(row, col - c);

              if (mask) {
                dark = !dark;
              }

              _modules[row][col - c] = dark;
              bitIndex -= 1;

              if (bitIndex == -1) {
                byteIndex += 1;
                bitIndex = 7;
              }
            }
          }

          row += inc;

          if (row < 0 || _moduleCount <= row) {
            row -= inc;
            inc = -inc;
            break;
          }
        }
      }
    };

    var createBytes = function(buffer, rsBlocks) {

      var offset = 0;

      var maxDcCount = 0;
      var maxEcCount = 0;

      var dcdata = new Array(rsBlocks.length);
      var ecdata = new Array(rsBlocks.length);

      for (var r = 0; r < rsBlocks.length; r += 1) {

        var dcCount = rsBlocks[r].dataCount;
        var ecCount = rsBlocks[r].totalCount - dcCount;

        maxDcCount = Math.max(maxDcCount, dcCount);
        maxEcCount = Math.max(maxEcCount, ecCount);

        dcdata[r] = new Array(dcCount);

        for (var i = 0; i < dcdata[r].length; i += 1) {
          dcdata[r][i] = 0xff & buffer.getBuffer()[i + offset];
        }
        offset += dcCount;

        var rsPoly = QRUtil.getErrorCorrectPolynomial(ecCount);
        var rawPoly = qrPolynomial(dcdata[r], rsPoly.getLength() - 1);

        var modPoly = rawPoly.mod(rsPoly);
        ecdata[r] = new Array(rsPoly.getLength() - 1);
        for (var i = 0; i < ecdata[r].length; i += 1) {
          var modIndex = i + modPoly.getLength() - ecdata[r].length;
          ecdata[r][i] = (modIndex >= 0)? modPoly.getAt(modIndex) : 0;
        }
      }

      var totalCodeCount = 0;
      for (var i = 0; i < rsBlocks.length; i += 1) {
        totalCodeCount += rsBlocks[i].totalCount;
      }

      var data = new Array(totalCodeCount);
      var index = 0;

      for (var i = 0; i < maxDcCount; i += 1) {
        for (var r = 0; r < rsBlocks.length; r += 1) {
          if (i < dcdata[r].length) {
            data[index] = dcdata[r][i];
            index += 1;
          }
        }
      }

      for (var i = 0; i < maxEcCount; i += 1) {
        for (var r = 0; r < rsBlocks.length; r += 1) {
          if (i < ecdata[r].length) {
            data[index] = ecdata[r][i];
            index += 1;
          }
        }
      }

      return data;
    };

    var createData = function(typeNumber, errorCorrectionLevel, dataList) {

      var rsBlocks = QRRSBlock.getRSBlocks(typeNumber, errorCorrectionLevel);

      var buffer = qrBitBuffer();

      for (var i = 0; i < dataList.length; i += 1) {
        var data = dataList[i];
        buffer.put(data.getMode(), 4);
        buffer.put(data.getLength(), QRUtil.getLengthInBits(data.getMode(), typeNumber) );
        data.write(buffer);
      }

      // calc num max data.
      var totalDataCount = 0;
      for (var i = 0; i < rsBlocks.length; i += 1) {
        totalDataCount += rsBlocks[i].dataCount;
      }

      if (buffer.getLengthInBits() > totalDataCount * 8) {
        throw 'code length overflow. ('
          + buffer.getLengthInBits()
          + '>'
          + totalDataCount * 8
          + ')';
      }

      // end code
      if (buffer.getLengthInBits() + 4 <= totalDataCount * 8) {
        buffer.put(0, 4);
      }

      // padding
      while (buffer.getLengthInBits() % 8 != 0) {
        buffer.putBit(false);
      }

      // padding
      while (true) {

        if (buffer.getLengthInBits() >= totalDataCount * 8) {
          break;
        }
        buffer.put(PAD0, 8);

        if (buffer.getLengthInBits() >= totalDataCount * 8) {
          break;
        }
        buffer.put(PAD1, 8);
      }

      return createBytes(buffer, rsBlocks);
    };

    _this.addData = function(data, mode) {

      mode = mode || 'Byte';

      var newData = null;

      switch(mode) {
      case 'Numeric' :
        newData = qrNumber(data);
        break;
      case 'Alphanumeric' :
        newData = qrAlphaNum(data);
        break;
      case 'Byte' :
        newData = qr8BitByte(data);
        break;
      case 'Kanji' :
        newData = qrKanji(data);
        break;
      default :
        throw 'mode:' + mode;
      }

      _dataList.push(newData);
      _dataCache = null;
    };

    _this.isDark = function(row, col) {
      if (row < 0 || _moduleCount <= row || col < 0 || _moduleCount <= col) {
        throw row + ',' + col;
      }
      return _modules[row][col];
    };

    _this.getModuleCount = function() {
      return _moduleCount;
    };

    _this.make = function() {
      if (_typeNumber < 1) {
        var typeNumber = 1;

        for (; typeNumber < 40; typeNumber++) {
          var rsBlocks = QRRSBlock.getRSBlocks(typeNumber, _errorCorrectionLevel);
          var buffer = qrBitBuffer();

          for (var i = 0; i < _dataList.length; i++) {
            var data = _dataList[i];
            buffer.put(data.getMode(), 4);
            buffer.put(data.getLength(), QRUtil.getLengthInBits(data.getMode(), typeNumber) );
            data.write(buffer);
          }

          var totalDataCount = 0;
          for (var i = 0; i < rsBlocks.length; i++) {
            totalDataCount += rsBlocks[i].dataCount;
          }

          if (buffer.getLengthInBits() <= totalDataCount * 8) {
            break;
          }
        }

        _typeNumber = typeNumber;
      }

      makeImpl(false, getBestMaskPattern() );
    };

    _this.createTableTag = function(cellSize, margin) {

      cellSize = cellSize || 2;
      margin = (typeof margin == 'undefined')? cellSize * 4 : margin;

      var qrHtml = '';

      qrHtml += '<table style="';
      qrHtml += ' border-width: 0px; border-style: none;';
      qrHtml += ' border-collapse: collapse;';
      qrHtml += ' padding: 0px; margin: ' + margin + 'px;';
      qrHtml += '">';
      qrHtml += '<tbody>';

      for (var r = 0; r < _this.getModuleCount(); r += 1) {

        qrHtml += '<tr>';

        for (var c = 0; c < _this.getModuleCount(); c += 1) {
          qrHtml += '<td style="';
          qrHtml += ' border-width: 0px; border-style: none;';
          qrHtml += ' border-collapse: collapse;';
          qrHtml += ' padding: 0px; margin: 0px;';
          qrHtml += ' width: ' + cellSize + 'px;';
          qrHtml += ' height: ' + cellSize + 'px;';
          qrHtml += ' background-color: ';
          qrHtml += _this.isDark(r, c)? '#000000' : '#ffffff';
          qrHtml += ';';
          qrHtml += '"/>';
        }

        qrHtml += '</tr>';
      }

      qrHtml += '</tbody>';
      qrHtml += '</table>';

      return qrHtml;
    };

    _this.createSvgTag = function(cellSize, margin, alt, title) {

      var opts = {};
      if (typeof arguments[0] == 'object') {
        // Called by options.
        opts = arguments[0];
        // overwrite cellSize and margin.
        cellSize = opts.cellSize;
        margin = opts.margin;
        alt = opts.alt;
        title = opts.title;
      }

      cellSize = cellSize || 2;
      margin = (typeof margin == 'undefined')? cellSize * 4 : margin;

      // Compose alt property surrogate
      alt = (typeof alt === 'string') ? {text: alt} : alt || {};
      alt.text = alt.text || null;
      alt.id = (alt.text) ? alt.id || 'qrcode-description' : null;

      // Compose title property surrogate
      title = (typeof title === 'string') ? {text: title} : title || {};
      title.text = title.text || null;
      title.id = (title.text) ? title.id || 'qrcode-title' : null;

      var size = _this.getModuleCount() * cellSize + margin * 2;
      var c, mc, r, mr, qrSvg='', rect;

      rect = 'l' + cellSize + ',0 0,' + cellSize +
        ' -' + cellSize + ',0 0,-' + cellSize + 'z ';

      qrSvg += '<svg version="1.1" xmlns="http://www.w3.org/2000/svg"';
      qrSvg += !opts.scalable ? ' width="' + size + 'px" height="' + size + 'px"' : '';
      qrSvg += ' viewBox="0 0 ' + size + ' ' + size + '" ';
      qrSvg += ' preserveAspectRatio="xMinYMin meet"';
      qrSvg += (title.text || alt.text) ? ' role="img" aria-labelledby="' +
          escapeXml([title.id, alt.id].join(' ').trim() ) + '"' : '';
      qrSvg += '>';
      qrSvg += (title.text) ? '<title id="' + escapeXml(title.id) + '">' +
          escapeXml(title.text) + '</title>' : '';
      qrSvg += (alt.text) ? '<description id="' + escapeXml(alt.id) + '">' +
          escapeXml(alt.text) + '</description>' : '';
      qrSvg += '<rect width="100%" height="100%" fill="white" cx="0" cy="0"/>';
      qrSvg += '<path d="';

      for (r = 0; r < _this.getModuleCount(); r += 1) {
        mr = r * cellSize + margin;
        for (c = 0; c < _this.getModuleCount(); c += 1) {
          if (_this.isDark(r, c) ) {
            mc = c*cellSize+margin;
            qrSvg += 'M' + mc + ',' + mr + rect;
          }
        }
      }

      qrSvg += '" stroke="transparent" fill="black"/>';
      qrSvg += '</svg>';

      return qrSvg;
    };

    _this.createDataURL = function(cellSize, margin) {

      cellSize = cellSize || 2;
      margin = (typeof margin == 'undefined')? cellSize * 4 : margin;

      var size = _this.getModuleCount() * cellSize + margin * 2;
      var min = margin;
      var max = size - margin;

      return createDataURL(size, size, function(x, y) {
        if (min <= x && x < max && min <= y && y < max) {
          var c = Math.floor( (x - min) / cellSize);
          var r = Math.floor( (y - min) / cellSize);
          return _this.isDark(r, c)? 0 : 1;
        } else {
          return 1;
        }
      } );
    };

    _this.createImgTag = function(cellSize, margin, alt) {

      cellSize = cellSize || 2;
      margin = (typeof margin == 'undefined')? cellSize * 4 : margin;

      var size = _this.getModuleCount() * cellSize + margin * 2;

      var img = '';
      img += '<img';
      img += '\u0020src="';
      img += _this.createDataURL(cellSize, margin);
      img += '"';
      img += '\u0020width="';
      img += size;
      img += '"';
      img += '\u0020height="';
      img += size;
      img += '"';
      if (alt) {
        img += '\u0020alt="';
        img += escapeXml(alt);
        img += '"';
      }
      img += '/>';

      return img;
    };

    var escapeXml = function(s) {
      var escaped = '';
      for (var i = 0; i < s.length; i += 1) {
        var c = s.charAt(i);
        switch(c) {
        case '<': escaped += '&lt;'; break;
        case '>': escaped += '&gt;'; break;
        case '&': escaped += '&amp;'; break;
        case '"': escaped += '&quot;'; break;
        default : escaped += c; break;
        }
      }
      return escaped;
    };

    var _createHalfASCII = function(margin) {
      var cellSize = 1;
      margin = (typeof margin == 'undefined')? cellSize * 2 : margin;

      var size = _this.getModuleCount() * cellSize + margin * 2;
      var min = margin;
      var max = size - margin;

      var y, x, r1, r2, p;

      var blocks = {
        '██': '█',
        '█ ': '▀',
        ' █': '▄',
        '  ': ' '
      };

      var blocksLastLineNoMargin = {
        '██': '▀',
        '█ ': '▀',
        ' █': ' ',
        '  ': ' '
      };

      var ascii = '';
      for (y = 0; y < size; y += 2) {
        r1 = Math.floor((y - min) / cellSize);
        r2 = Math.floor((y + 1 - min) / cellSize);
        for (x = 0; x < size; x += 1) {
          p = '█';

          if (min <= x && x < max && min <= y && y < max && _this.isDark(r1, Math.floor((x - min) / cellSize))) {
            p = ' ';
          }

          if (min <= x && x < max && min <= y+1 && y+1 < max && _this.isDark(r2, Math.floor((x - min) / cellSize))) {
            p += ' ';
          }
          else {
            p += '█';
          }

          // Output 2 characters per pixel, to create full square. 1 character per pixels gives only half width of square.
          ascii += (margin < 1 && y+1 >= max) ? blocksLastLineNoMargin[p] : blocks[p];
        }

        ascii += '\n';
      }

      if (size % 2 && margin > 0) {
        return ascii.substring(0, ascii.length - size - 1) + Array(size+1).join('▀');
      }

      return ascii.substring(0, ascii.length-1);
    };

    _this.createASCII = function(cellSize, margin) {
      cellSize = cellSize || 1;

      if (cellSize < 2) {
        return _createHalfASCII(margin);
      }

      cellSize -= 1;
      margin = (typeof margin == 'undefined')? cellSize * 2 : margin;

      var size = _this.getModuleCount() * cellSize + margin * 2;
      var min = margin;
      var max = size - margin;

      var y, x, r, p;

      var white = Array(cellSize+1).join('██');
      var black = Array(cellSize+1).join('  ');

      var ascii = '';
      var line = '';
      for (y = 0; y < size; y += 1) {
        r = Math.floor( (y - min) / cellSize);
        line = '';
        for (x = 0; x < size; x += 1) {
          p = 1;

          if (min <= x && x < max && min <= y && y < max && _this.isDark(r, Math.floor((x - min) / cellSize))) {
            p = 0;
          }

          // Output 2 characters per pixel, to create full square. 1 character per pixels gives only half width of square.
          line += p ? white : black;
        }

        for (r = 0; r < cellSize; r += 1) {
          ascii += line + '\n';
        }
      }

      return ascii.substring(0, ascii.length-1);
    };

    _this.renderTo2dContext = function(context, cellSize) {
      cellSize = cellSize || 2;
      var length = _this.getModuleCount();
      for (var row = 0; row < length; row++) {
        for (var col = 0; col < length; col++) {
          context.fillStyle = _this.isDark(row, col) ? 'black' : 'white';
          context.fillRect(row * cellSize, col * cellSize, cellSize, cellSize);
        }
      }
    }

    return _this;
  };

  //---------------------------------------------------------------------
  // qrcode.stringToBytes
  //---------------------------------------------------------------------

  qrcode.stringToBytesFuncs = {
    'default' : function(s) {
      var bytes = [];
      for (var i = 0; i < s.length; i += 1) {
        var c = s.charCodeAt(i);
        bytes.push(c & 0xff);
      }
      return bytes;
    }
  };

  qrcode.stringToBytes = qrcode.stringToBytesFuncs['default'];

  //---------------------------------------------------------------------
  // qrcode.createStringToBytes
  //---------------------------------------------------------------------

  /**
   * @param unicodeData base64 string of byte array.
   * [16bit Unicode],[16bit Bytes], ...
   * @param numChars
   */
  qrcode.createStringToBytes = function(unicodeData, numChars) {

    // create conversion map.

    var unicodeMap = function() {

      var bin = base64DecodeInputStream(unicodeData);
      var read = function() {
        var b = bin.read();
        if (b == -1) throw 'eof';
        return b;
      };

      var count = 0;
      var unicodeMap = {};
      while (true) {
        var b0 = bin.read();
        if (b0 == -1) break;
        var b1 = read();
        var b2 = read();
        var b3 = read();
        var k = String.fromCharCode( (b0 << 8) | b1);
        var v = (b2 << 8) | b3;
        unicodeMap[k] = v;
        count += 1;
      }
      if (count != numChars) {
        throw count + ' != ' + numChars;
      }

      return unicodeMap;
    }();

    var unknownChar = '?'.charCodeAt(0);

    return function(s) {
      var bytes = [];
      for (var i = 0; i < s.length; i += 1) {
        var c = s.charCodeAt(i);
        if (c < 128) {
          bytes.push(c);
        } else {
          var b = unicodeMap[s.charAt(i)];
          if (typeof b == 'number') {
            if ( (b & 0xff) == b) {
              // 1byte
              bytes.push(b);
            } else {
              // 2bytes
              bytes.push(b >>> 8);
              bytes.push(b & 0xff);
            }
          } else {
            bytes.push(unknownChar);
          }
        }
      }
      return bytes;
    };
  };

  //---------------------------------------------------------------------
  // QRMode
  //---------------------------------------------------------------------

  var QRMode = {
    MODE_NUMBER :    1 << 0,
    MODE_ALPHA_NUM : 1 << 1,
    MODE_8BIT_BYTE : 1 << 2,
    MODE_KANJI :     1 << 3
  };

  //---------------------------------------------------------------------
  // QRErrorCorrectionLevel
  //---------------------------------------------------------------------

  var QRErrorCorrectionLevel = {
    L : 1,
    M : 0,
    Q : 3,
    H : 2
  };

  //---------------------------------------------------------------------
  // QRMaskPattern
  //---------------------------------------------------------------------

  var QRMaskPattern = {
    PATTERN000 : 0,
    PATTERN001 : 1,
    PATTERN010 : 2,
    PATTERN011 : 3,
    PATTERN100 : 4,
    PATTERN101 : 5,
    PATTERN110 : 6,
    PATTERN111 : 7
  };

  //---------------------------------------------------------------------
  // QRUtil
  //---------------------------------------------------------------------

  var QRUtil = function() {

    var PATTERN_POSITION_TABLE = [
      [],
      [6, 18],
      [6, 22],
      [6, 26],
      [6, 30],
      [6, 34],
      [6, 22, 38],
      [6, 24, 42],
      [6, 26, 46],
      [6, 28, 50],
      [6, 30, 54],
      [6, 32, 58],
      [6, 34, 62],
      [6, 26, 46, 66],
      [6, 26, 48, 70],
      [6, 26, 50, 74],
      [6, 30, 54, 78],
      [6, 30, 56, 82],
      [6, 30, 58, 86],
      [6, 34, 62, 90],
      [6, 28, 50, 72, 94],
      [6, 26, 50, 74, 98],
      [6, 30, 54, 78, 102],
      [6, 28, 54, 80, 106],
      [6, 32, 58, 84, 110],
      [6, 30, 58, 86, 114],
      [6, 34, 62, 90, 118],
      [6, 26, 50, 74, 98, 122],
      [6, 30, 54, 78, 102, 126],
      [6, 26, 52, 78, 104, 130],
      [6, 30, 56, 82, 108, 134],
      [6, 34, 60, 86, 112, 138],
      [6, 30, 58, 86, 114, 142],
      [6, 34, 62, 90, 118, 146],
      [6, 30, 54, 78, 102, 126, 150],
      [6, 24, 50, 76, 102, 128, 154],
      [6, 28, 54, 80, 106, 132, 158],
      [6, 32, 58, 84, 110, 136, 162],
      [6, 26, 54, 82, 110, 138, 166],
      [6, 30, 58, 86, 114, 142, 170]
    ];
    var G15 = (1 << 10) | (1 << 8) | (1 << 5) | (1 << 4) | (1 << 2) | (1 << 1) | (1 << 0);
    var G18 = (1 << 12) | (1 << 11) | (1 << 10) | (1 << 9) | (1 << 8) | (1 << 5) | (1 << 2) | (1 << 0);
    var G15_MASK = (1 << 14) | (1 << 12) | (1 << 10) | (1 << 4) | (1 << 1);

    var _this = {};

    var getBCHDigit = function(data) {
      var digit = 0;
      while (data != 0) {
        digit += 1;
        data >>>= 1;
      }
      return digit;
    };

    _this.getBCHTypeInfo = function(data) {
      var d = data << 10;
      while (getBCHDigit(d) - getBCHDigit(G15) >= 0) {
        d ^= (G15 << (getBCHDigit(d) - getBCHDigit(G15) ) );
      }
      return ( (data << 10) | d) ^ G15_MASK;
    };

    _this.getBCHTypeNumber = function(data) {
      var d = data << 12;
      while (getBCHDigit(d) - getBCHDigit(G18) >= 0) {
        d ^= (G18 << (getBCHDigit(d) - getBCHDigit(G18) ) );
      }
      return (data << 12) | d;
    };

    _this.getPatternPosition = function(typeNumber) {
      return PATTERN_POSITION_TABLE[typeNumber - 1];
    };

    _this.getMaskFunction = function(maskPattern) {

      switch (maskPattern) {

      case QRMaskPattern.PATTERN000 :
        return function(i, j) { return (i + j) % 2 == 0; };
      case QRMaskPattern.PATTERN001 :
        return function(i, j) { return i % 2 == 0; };
      case QRMaskPattern.PATTERN010 :
        return function(i, j) { return j % 3 == 0; };
      case QRMaskPattern.PATTERN011 :
        return function(i, j) { return (i + j) % 3 == 0; };
      case QRMaskPattern.PATTERN100 :
        return function(i, j) { return (Math.floor(i / 2) + Math.floor(j / 3) ) % 2 == 0; };
      case QRMaskPattern.PATTERN101 :
        return function(i, j) { return (i * j) % 2 + (i * j) % 3 == 0; };
      case QRMaskPattern.PATTERN110 :
        return function(i, j) { return ( (i * j) % 2 + (i * j) % 3) % 2 == 0; };
      case QRMaskPattern.PATTERN111 :
        return function(i, j) { return ( (i * j) % 3 + (i + j) % 2) % 2 == 0; };

      default :
        throw 'bad maskPattern:' + maskPattern;
      }
    };

    _this.getErrorCorrectPolynomial = function(errorCorrectLength) {
      var a = qrPolynomial([1], 0);
      for (var i = 0; i < errorCorrectLength; i += 1) {
        a = a.multiply(qrPolynomial([1, QRMath.gexp(i)], 0) );
      }
      return a;
    };

    _this.getLengthInBits = function(mode, type) {

      if (1 <= type && type < 10) {

        // 1 - 9

        switch(mode) {
        case QRMode.MODE_NUMBER    : return 10;
        case QRMode.MODE_ALPHA_NUM : return 9;
        case QRMode.MODE_8BIT_BYTE : return 8;
        case QRMode.MODE_KANJI     : return 8;
        default :
          throw 'mode:' + mode;
        }

      } else if (type < 27) {

        // 10 - 26

        switch(mode) {
        case QRMode.MODE_NUMBER    : return 12;
        case QRMode.MODE_ALPHA_NUM : return 11;
        case QRMode.MODE_8BIT_BYTE : return 16;
        case QRMode.MODE_KANJI     : return 10;
        default :
          throw 'mode:' + mode;
        }

      } else if (type < 41) {

        // 27 - 40

        switch(mode) {
        case QRMode.MODE_NUMBER    : return 14;
        case QRMode.MODE_ALPHA_NUM : return 13;
        case QRMode.MODE_8BIT_BYTE : return 16;
        case QRMode.MODE_KANJI     : return 12;
        default :
          throw 'mode:' + mode;
        }

      } else {
        throw 'type:' + type;
      }
    };

    _this.getLostPoint = function(qrcode) {

      var moduleCount = qrcode.getModuleCount();

      var lostPoint = 0;

      // LEVEL1

      for (var row = 0; row < moduleCount; row += 1) {
        for (var col = 0; col < moduleCount; col += 1) {

          var sameCount = 0;
          var dark = qrcode.isDark(row, col);

          for (var r = -1; r <= 1; r += 1) {

            if (row + r < 0 || moduleCount <= row + r) {
              continue;
            }

            for (var c = -1; c <= 1; c += 1) {

              if (col + c < 0 || moduleCount <= col + c) {
                continue;
              }

              if (r == 0 && c == 0) {
                continue;
              }

              if (dark == qrcode.isDark(row + r, col + c) ) {
                sameCount += 1;
              }
            }
          }

          if (sameCount > 5) {
            lostPoint += (3 + sameCount - 5);
          }
        }
      };

      // LEVEL2

      for (var row = 0; row < moduleCount - 1; row += 1) {
        for (var col = 0; col < moduleCount - 1; col += 1) {
          var count = 0;
          if (qrcode.isDark(row, col) ) count += 1;
          if (qrcode.isDark(row + 1, col) ) count += 1;
          if (qrcode.isDark(row, col + 1) ) count += 1;
          if (qrcode.isDark(row + 1, col + 1) ) count += 1;
          if (count == 0 || count == 4) {
            lostPoint += 3;
          }
        }
      }

      // LEVEL3

      for (var row = 0; row < moduleCount; row += 1) {
        for (var col = 0; col < moduleCount - 6; col += 1) {
          if (qrcode.isDark(row, col)
              && !qrcode.isDark(row, col + 1)
              &&  qrcode.isDark(row, col + 2)
              &&  qrcode.isDark(row, col + 3)
              &&  qrcode.isDark(row, col + 4)
              && !qrcode.isDark(row, col + 5)
              &&  qrcode.isDark(row, col + 6) ) {
            lostPoint += 40;
          }
        }
      }

      for (var col = 0; col < moduleCount; col += 1) {
        for (var row = 0; row < moduleCount - 6; row += 1) {
          if (qrcode.isDark(row, col)
              && !qrcode.isDark(row + 1, col)
              &&  qrcode.isDark(row + 2, col)
              &&  qrcode.isDark(row + 3, col)
              &&  qrcode.isDark(row + 4, col)
              && !qrcode.isDark(row + 5, col)
              &&  qrcode.isDark(row + 6, col) ) {
            lostPoint += 40;
          }
        }
      }

      // LEVEL4

      var darkCount = 0;

      for (var col = 0; col < moduleCount; col += 1) {
        for (var row = 0; row < moduleCount; row += 1) {
          if (qrcode.isDark(row, col) ) {
            darkCount += 1;
          }
        }
      }

      var ratio = Math.abs(100 * darkCount / moduleCount / moduleCount - 50) / 5;
      lostPoint += ratio * 10;

      return lostPoint;
    };

    return _this;
  }();

  //---------------------------------------------------------------------
  // QRMath
  //---------------------------------------------------------------------

  var QRMath = function() {

    var EXP_TABLE = new Array(256);
    var LOG_TABLE = new Array(256);

    // initialize tables
    for (var i = 0; i < 8; i += 1) {
      EXP_TABLE[i] = 1 << i;
    }
    for (var i = 8; i < 256; i += 1) {
      EXP_TABLE[i] = EXP_TABLE[i - 4]
        ^ EXP_TABLE[i - 5]
        ^ EXP_TABLE[i - 6]
        ^ EXP_TABLE[i - 8];
    }
    for (var i = 0; i < 255; i += 1) {
      LOG_TABLE[EXP_TABLE[i] ] = i;
    }

    var _this = {};

    _this.glog = function(n) {

      if (n < 1) {
        throw 'glog(' + n + ')';
      }

      return LOG_TABLE[n];
    };

    _this.gexp = function(n) {

      while (n < 0) {
        n += 255;
      }

      while (n >= 256) {
        n -= 255;
      }

      return EXP_TABLE[n];
    };

    return _this;
  }();

  //---------------------------------------------------------------------
  // qrPolynomial
  //---------------------------------------------------------------------

  function qrPolynomial(num, shift) {

    if (typeof num.length == 'undefined') {
      throw num.length + '/' + shift;
    }

    var _num = function() {
      var offset = 0;
      while (offset < num.length && num[offset] == 0) {
        offset += 1;
      }
      var _num = new Array(num.length - offset + shift);
      for (var i = 0; i < num.length - offset; i += 1) {
        _num[i] = num[i + offset];
      }
      return _num;
    }();

    var _this = {};

    _this.getAt = function(index) {
      return _num[index];
    };

    _this.getLength = function() {
      return _num.length;
    };

    _this.multiply = function(e) {

      var num = new Array(_this.getLength() + e.getLength() - 1);

      for (var i = 0; i < _this.getLength(); i += 1) {
        for (var j = 0; j < e.getLength(); j += 1) {
          num[i + j] ^= QRMath.gexp(QRMath.glog(_this.getAt(i) ) + QRMath.glog(e.getAt(j) ) );
        }
      }

      return qrPolynomial(num, 0);
    };

    _this.mod = function(e) {

      if (_this.getLength() - e.getLength() < 0) {
        return _this;
      }

      var ratio = QRMath.glog(_this.getAt(0) ) - QRMath.glog(e.getAt(0) );

      var num = new Array(_this.getLength() );
      for (var i = 0; i < _this.getLength(); i += 1) {
        num[i] = _this.getAt(i);
      }

      for (var i = 0; i < e.getLength(); i += 1) {
        num[i] ^= QRMath.gexp(QRMath.glog(e.getAt(i) ) + ratio);
      }

      // recursive call
      return qrPolynomial(num, 0).mod(e);
    };

    return _this;
  };

  //---------------------------------------------------------------------
  // QRRSBlock
  //---------------------------------------------------------------------

  var QRRSBlock = function() {

    var RS_BLOCK_TABLE = [

      // L
      // M
      // Q
      // H

      // 1
      [1, 26, 19],
      [1, 26, 16],
      [1, 26, 13],
      [1, 26, 9],

      // 2
      [1, 44, 34],
      [1, 44, 28],
      [1, 44, 22],
      [1, 44, 16],

      // 3
      [1, 70, 55],
      [1, 70, 44],
      [2, 35, 17],
      [2, 35, 13],

      // 4
      [1, 100, 80],
      [2, 50, 32],
      [2, 50, 24],
      [4, 25, 9],

      // 5
      [1, 134, 108],
      [2, 67, 43],
      [2, 33, 15, 2, 34, 16],
      [2, 33, 11, 2, 34, 12],

      // 6
      [2, 86, 68],
      [4, 43, 27],
      [4, 43, 19],
      [4, 43, 15],

      // 7
      [2, 98, 78],
      [4, 49, 31],
      [2, 32, 14, 4, 33, 15],
      [4, 39, 13, 1, 40, 14],

      // 8
      [2, 121, 97],
      [2, 60, 38, 2, 61, 39],
      [4, 40, 18, 2, 41, 19],
      [4, 40, 14, 2, 41, 15],

      // 9
      [2, 146, 116],
      [3, 58, 36, 2, 59, 37],
      [4, 36, 16, 4, 37, 17],
      [4, 36, 12, 4, 37, 13],

      // 10
      [2, 86, 68, 2, 87, 69],
      [4, 69, 43, 1, 70, 44],
      [6, 43, 19, 2, 44, 20],
      [6, 43, 15, 2, 44, 16],

      // 11
      [4, 101, 81],
      [1, 80, 50, 4, 81, 51],
      [4, 50, 22, 4, 51, 23],
      [3, 36, 12, 8, 37, 13],

      // 12
      [2, 116, 92, 2, 117, 93],
      [6, 58, 36, 2, 59, 37],
      [4, 46, 20, 6, 47, 21],
      [7, 42, 14, 4, 43, 15],

      // 13
      [4, 133, 107],
      [8, 59, 37, 1, 60, 38],
      [8, 44, 20, 4, 45, 21],
      [12, 33, 11, 4, 34, 12],

      // 14
      [3, 145, 115, 1, 146, 116],
      [4, 64, 40, 5, 65, 41],
      [11, 36, 16, 5, 37, 17],
      [11, 36, 12, 5, 37, 13],

      // 15
      [5, 109, 87, 1, 110, 88],
      [5, 65, 41, 5, 66, 42],
      [5, 54, 24, 7, 55, 25],
      [11, 36, 12, 7, 37, 13],

      // 16
      [5, 122, 98, 1, 123, 99],
      [7, 73, 45, 3, 74, 46],
      [15, 43, 19, 2, 44, 20],
      [3, 45, 15, 13, 46, 16],

      // 17
      [1, 135, 107, 5, 136, 108],
      [10, 74, 46, 1, 75, 47],
      [1, 50, 22, 15, 51, 23],
      [2, 42, 14, 17, 43, 15],

      // 18
      [5, 150, 120, 1, 151, 121],
      [9, 69, 43, 4, 70, 44],
      [17, 50, 22, 1, 51, 23],
      [2, 42, 14, 19, 43, 15],

      // 19
      [3, 141, 113, 4, 142, 114],
      [3, 70, 44, 11, 71, 45],
      [17, 47, 21, 4, 48, 22],
      [9, 39, 13, 16, 40, 14],

      // 20
      [3, 135, 107, 5, 136, 108],
      [3, 67, 41, 13, 68, 42],
      [15, 54, 24, 5, 55, 25],
      [15, 43, 15, 10, 44, 16],

      // 21
      [4, 144, 116, 4, 145, 117],
      [17, 68, 42],
      [17, 50, 22, 6, 51, 23],
      [19, 46, 16, 6, 47, 17],

      // 22
      [2, 139, 111, 7, 140, 112],
      [17, 74, 46],
      [7, 54, 24, 16, 55, 25],
      [34, 37, 13],

      // 23
      [4, 151, 121, 5, 152, 122],
      [4, 75, 47, 14, 76, 48],
      [11, 54, 24, 14, 55, 25],
      [16, 45, 15, 14, 46, 16],

      // 24
      [6, 147, 117, 4, 148, 118],
      [6, 73, 45, 14, 74, 46],
      [11, 54, 24, 16, 55, 25],
      [30, 46, 16, 2, 47, 17],

      // 25
      [8, 132, 106, 4, 133, 107],
      [8, 75, 47, 13, 76, 48],
      [7, 54, 24, 22, 55, 25],
      [22, 45, 15, 13, 46, 16],

      // 26
      [10, 142, 114, 2, 143, 115],
      [19, 74, 46, 4, 75, 47],
      [28, 50, 22, 6, 51, 23],
      [33, 46, 16, 4, 47, 17],

      // 27
      [8, 152, 122, 4, 153, 123],
      [22, 73, 45, 3, 74, 46],
      [8, 53, 23, 26, 54, 24],
      [12, 45, 15, 28, 46, 16],

      // 28
      [3, 147, 117, 10, 148, 118],
      [3, 73, 45, 23, 74, 46],
      [4, 54, 24, 31, 55, 25],
      [11, 45, 15, 31, 46, 16],

      // 29
      [7, 146, 116, 7, 147, 117],
      [21, 73, 45, 7, 74, 46],
      [1, 53, 23, 37, 54, 24],
      [19, 45, 15, 26, 46, 16],

      // 30
      [5, 145, 115, 10, 146, 116],
      [19, 75, 47, 10, 76, 48],
      [15, 54, 24, 25, 55, 25],
      [23, 45, 15, 25, 46, 16],

      // 31
      [13, 145, 115, 3, 146, 116],
      [2, 74, 46, 29, 75, 47],
      [42, 54, 24, 1, 55, 25],
      [23, 45, 15, 28, 46, 16],

      // 32
      [17, 145, 115],
      [10, 74, 46, 23, 75, 47],
      [10, 54, 24, 35, 55, 25],
      [19, 45, 15, 35, 46, 16],

      // 33
      [17, 145, 115, 1, 146, 116],
      [14, 74, 46, 21, 75, 47],
      [29, 54, 24, 19, 55, 25],
      [11, 45, 15, 46, 46, 16],

      // 34
      [13, 145, 115, 6, 146, 116],
      [14, 74, 46, 23, 75, 47],
      [44, 54, 24, 7, 55, 25],
      [59, 46, 16, 1, 47, 17],

      // 35
      [12, 151, 121, 7, 152, 122],
      [12, 75, 47, 26, 76, 48],
      [39, 54, 24, 14, 55, 25],
      [22, 45, 15, 41, 46, 16],

      // 36
      [6, 151, 121, 14, 152, 122],
      [6, 75, 47, 34, 76, 48],
      [46, 54, 24, 10, 55, 25],
      [2, 45, 15, 64, 46, 16],

      // 37
      [17, 152, 122, 4, 153, 123],
      [29, 74, 46, 14, 75, 47],
      [49, 54, 24, 10, 55, 25],
      [24, 45, 15, 46, 46, 16],

      // 38
      [4, 152, 122, 18, 153, 123],
      [13, 74, 46, 32, 75, 47],
      [48, 54, 24, 14, 55, 25],
      [42, 45, 15, 32, 46, 16],

      // 39
      [20, 147, 117, 4, 148, 118],
      [40, 75, 47, 7, 76, 48],
      [43, 54, 24, 22, 55, 25],
      [10, 45, 15, 67, 46, 16],

      // 40
      [19, 148, 118, 6, 149, 119],
      [18, 75, 47, 31, 76, 48],
      [34, 54, 24, 34, 55, 25],
      [20, 45, 15, 61, 46, 16]
    ];

    var qrRSBlock = function(totalCount, dataCount) {
      var _this = {};
      _this.totalCount = totalCount;
      _this.dataCount = dataCount;
      return _this;
    };

    var _this = {};

    var getRsBlockTable = function(typeNumber, errorCorrectionLevel) {

      switch(errorCorrectionLevel) {
      case QRErrorCorrectionLevel.L :
        return RS_BLOCK_TABLE[(typeNumber - 1) * 4 + 0];
      case QRErrorCorrectionLevel.M :
        return RS_BLOCK_TABLE[(typeNumber - 1) * 4 + 1];
      case QRErrorCorrectionLevel.Q :
        return RS_BLOCK_TABLE[(typeNumber - 1) * 4 + 2];
      case QRErrorCorrectionLevel.H :
        return RS_BLOCK_TABLE[(typeNumber - 1) * 4 + 3];
      default :
        return undefined;
      }
    };

    _this.getRSBlocks = function(typeNumber, errorCorrectionLevel) {

      var rsBlock = getRsBlockTable(typeNumber, errorCorrectionLevel);

      if (typeof rsBlock == 'undefined') {
        throw 'bad rs block @ typeNumber:' + typeNumber +
            '/errorCorrectionLevel:' + errorCorrectionLevel;
      }

      var length = rsBlock.length / 3;

      var list = [];

      for (var i = 0; i < length; i += 1) {

        var count = rsBlock[i * 3 + 0];
        var totalCount = rsBlock[i * 3 + 1];
        var dataCount = rsBlock[i * 3 + 2];

        for (var j = 0; j < count; j += 1) {
          list.push(qrRSBlock(totalCount, dataCount) );
        }
      }

      return list;
    };

    return _this;
  }();

  //---------------------------------------------------------------------
  // qrBitBuffer
  //---------------------------------------------------------------------

  var qrBitBuffer = function() {

    var _buffer = [];
    var _length = 0;

    var _this = {};

    _this.getBuffer = function() {
      return _buffer;
    };

    _this.getAt = function(index) {
      var bufIndex = Math.floor(index / 8);
      return ( (_buffer[bufIndex] >>> (7 - index % 8) ) & 1) == 1;
    };

    _this.put = function(num, length) {
      for (var i = 0; i < length; i += 1) {
        _this.putBit( ( (num >>> (length - i - 1) ) & 1) == 1);
      }
    };

    _this.getLengthInBits = function() {
      return _length;
    };

    _this.putBit = function(bit) {

      var bufIndex = Math.floor(_length / 8);
      if (_buffer.length <= bufIndex) {
        _buffer.push(0);
      }

      if (bit) {
        _buffer[bufIndex] |= (0x80 >>> (_length % 8) );
      }

      _length += 1;
    };

    return _this;
  };

  //---------------------------------------------------------------------
  // qrNumber
  //---------------------------------------------------------------------

  var qrNumber = function(data) {

    var _mode = QRMode.MODE_NUMBER;
    var _data = data;

    var _this = {};

    _this.getMode = function() {
      return _mode;
    };

    _this.getLength = function(buffer) {
      return _data.length;
    };

    _this.write = function(buffer) {

      var data = _data;

      var i = 0;

      while (i + 2 < data.length) {
        buffer.put(strToNum(data.substring(i, i + 3) ), 10);
        i += 3;
      }

      if (i < data.length) {
        if (data.length - i == 1) {
          buffer.put(strToNum(data.substring(i, i + 1) ), 4);
        } else if (data.length - i == 2) {
          buffer.put(strToNum(data.substring(i, i + 2) ), 7);
        }
      }
    };

    var strToNum = function(s) {
      var num = 0;
      for (var i = 0; i < s.length; i += 1) {
        num = num * 10 + chatToNum(s.charAt(i) );
      }
      return num;
    };

    var chatToNum = function(c) {
      if ('0' <= c && c <= '9') {
        return c.charCodeAt(0) - '0'.charCodeAt(0);
      }
      throw 'illegal char :' + c;
    };

    return _this;
  };

  //---------------------------------------------------------------------
  // qrAlphaNum
  //---------------------------------------------------------------------

  var qrAlphaNum = function(data) {

    var _mode = QRMode.MODE_ALPHA_NUM;
    var _data = data;

    var _this = {};

    _this.getMode = function() {
      return _mode;
    };

    _this.getLength = function(buffer) {
      return _data.length;
    };

    _this.write = function(buffer) {

      var s = _data;

      var i = 0;

      while (i + 1 < s.length) {
        buffer.put(
          getCode(s.charAt(i) ) * 45 +
          getCode(s.charAt(i + 1) ), 11);
        i += 2;
      }

      if (i < s.length) {
        buffer.put(getCode(s.charAt(i) ), 6);
      }
    };

    var getCode = function(c) {

      if ('0' <= c && c <= '9') {
        return c.charCodeAt(0) - '0'.charCodeAt(0);
      } else if ('A' <= c && c <= 'Z') {
        return c.charCodeAt(0) - 'A'.charCodeAt(0) + 10;
      } else {
        switch (c) {
        case ' ' : return 36;
        case '$' : return 37;
        case '%' : return 38;
        case '*' : return 39;
        case '+' : return 40;
        case '-' : return 41;
        case '.' : return 42;
        case '/' : return 43;
        case ':' : return 44;
        default :
          throw 'illegal char :' + c;
        }
      }
    };

    return _this;
  };

  //---------------------------------------------------------------------
  // qr8BitByte
  //---------------------------------------------------------------------

  var qr8BitByte = function(data) {

    var _mode = QRMode.MODE_8BIT_BYTE;
    var _data = data;
    var _bytes = qrcode.stringToBytes(data);

    var _this = {};

    _this.getMode = function() {
      return _mode;
    };

    _this.getLength = function(buffer) {
      return _bytes.length;
    };

    _this.write = function(buffer) {
      for (var i = 0; i < _bytes.length; i += 1) {
        buffer.put(_bytes[i], 8);
      }
    };

    return _this;
  };

  //---------------------------------------------------------------------
  // qrKanji
  //---------------------------------------------------------------------

  var qrKanji = function(data) {

    var _mode = QRMode.MODE_KANJI;
    var _data = data;

    var stringToBytes = qrcode.stringToBytesFuncs['SJIS'];
    if (!stringToBytes) {
      throw 'sjis not supported.';
    }
    !function(c, code) {
      // self test for sjis support.
      var test = stringToBytes(c);
      if (test.length != 2 || ( (test[0] << 8) | test[1]) != code) {
        throw 'sjis not supported.';
      }
    }('\u53cb', 0x9746);

    var _bytes = stringToBytes(data);

    var _this = {};

    _this.getMode = function() {
      return _mode;
    };

    _this.getLength = function(buffer) {
      return ~~(_bytes.length / 2);
    };

    _this.write = function(buffer) {

      var data = _bytes;

      var i = 0;

      while (i + 1 < data.length) {

        var c = ( (0xff & data[i]) << 8) | (0xff & data[i + 1]);

        if (0x8140 <= c && c <= 0x9FFC) {
          c -= 0x8140;
        } else if (0xE040 <= c && c <= 0xEBBF) {
          c -= 0xC140;
        } else {
          throw 'illegal char at ' + (i + 1) + '/' + c;
        }

        c = ( (c >>> 8) & 0xff) * 0xC0 + (c & 0xff);

        buffer.put(c, 13);

        i += 2;
      }

      if (i < data.length) {
        throw 'illegal char at ' + (i + 1);
      }
    };

    return _this;
  };

  //=====================================================================
  // GIF Support etc.
  //

  //---------------------------------------------------------------------
  // byteArrayOutputStream
  //---------------------------------------------------------------------

  var byteArrayOutputStream = function() {

    var _bytes = [];

    var _this = {};

    _this.writeByte = function(b) {
      _bytes.push(b & 0xff);
    };

    _this.writeShort = function(i) {
      _this.writeByte(i);
      _this.writeByte(i >>> 8);
    };

    _this.writeBytes = function(b, off, len) {
      off = off || 0;
      len = len || b.length;
      for (var i = 0; i < len; i += 1) {
        _this.writeByte(b[i + off]);
      }
    };

    _this.writeString = function(s) {
      for (var i = 0; i < s.length; i += 1) {
        _this.writeByte(s.charCodeAt(i) );
      }
    };

    _this.toByteArray = function() {
      return _bytes;
    };

    _this.toString = function() {
      var s = '';
      s += '[';
      for (var i = 0; i < _bytes.length; i += 1) {
        if (i > 0) {
          s += ',';
        }
        s += _bytes[i];
      }
      s += ']';
      return s;
    };

    return _this;
  };

  //---------------------------------------------------------------------
  // base64EncodeOutputStream
  //---------------------------------------------------------------------

  var base64EncodeOutputStream = function() {

    var _buffer = 0;
    var _buflen = 0;
    var _length = 0;
    var _base64 = '';

    var _this = {};

    var writeEncoded = function(b) {
      _base64 += String.fromCharCode(encode(b & 0x3f) );
    };

    var encode = function(n) {
      if (n < 0) {
        // error.
      } else if (n < 26) {
        return 0x41 + n;
      } else if (n < 52) {
        return 0x61 + (n - 26);
      } else if (n < 62) {
        return 0x30 + (n - 52);
      } else if (n == 62) {
        return 0x2b;
      } else if (n == 63) {
        return 0x2f;
      }
      throw 'n:' + n;
    };

    _this.writeByte = function(n) {

      _buffer = (_buffer << 8) | (n & 0xff);
      _buflen += 8;
      _length += 1;

      while (_buflen >= 6) {
        writeEncoded(_buffer >>> (_buflen - 6) );
        _buflen -= 6;
      }
    };

    _this.flush = function() {

      if (_buflen > 0) {
        writeEncoded(_buffer << (6 - _buflen) );
        _buffer = 0;
        _buflen = 0;
      }

      if (_length % 3 != 0) {
        // padding
        var padlen = 3 - _length % 3;
        for (var i = 0; i < padlen; i += 1) {
          _base64 += '=';
        }
      }
    };

    _this.toString = function() {
      return _base64;
    };

    return _this;
  };

  //---------------------------------------------------------------------
  // base64DecodeInputStream
  //---------------------------------------------------------------------

  var base64DecodeInputStream = function(str) {

    var _str = str;
    var _pos = 0;
    var _buffer = 0;
    var _buflen = 0;

    var _this = {};

    _this.read = function() {

      while (_buflen < 8) {

        if (_pos >= _str.length) {
          if (_buflen == 0) {
            return -1;
          }
          throw 'unexpected end of file./' + _buflen;
        }

        var c = _str.charAt(_pos);
        _pos += 1;

        if (c == '=') {
          _buflen = 0;
          return -1;
        } else if (c.match(/^\s$/) ) {
          // ignore if whitespace.
          continue;
        }

        _buffer = (_buffer << 6) | decode(c.charCodeAt(0) );
        _buflen += 6;
      }

      var n = (_buffer >>> (_buflen - 8) ) & 0xff;
      _buflen -= 8;
      return n;
    };

    var decode = function(c) {
      if (0x41 <= c && c <= 0x5a) {
        return c - 0x41;
      } else if (0x61 <= c && c <= 0x7a) {
        return c - 0x61 + 26;
      } else if (0x30 <= c && c <= 0x39) {
        return c - 0x30 + 52;
      } else if (c == 0x2b) {
        return 62;
      } else if (c == 0x2f) {
        return 63;
      } else {
        throw 'c:' + c;
      }
    };

    return _this;
  };

  //---------------------------------------------------------------------
  // gifImage (B/W)
  //---------------------------------------------------------------------

  var gifImage = function(width, height) {

    var _width = width;
    var _height = height;
    var _data = new Array(width * height);

    var _this = {};

    _this.setPixel = function(x, y, pixel) {
      _data[y * _width + x] = pixel;
    };

    _this.write = function(out) {

      //---------------------------------
      // GIF Signature

      out.writeString('GIF87a');

      //---------------------------------
      // Screen Descriptor

      out.writeShort(_width);
      out.writeShort(_height);

      out.writeByte(0x80); // 2bit
      out.writeByte(0);
      out.writeByte(0);

      //---------------------------------
      // Global Color Map

      // black
      out.writeByte(0x00);
      out.writeByte(0x00);
      out.writeByte(0x00);

      // white
      out.writeByte(0xff);
      out.writeByte(0xff);
      out.writeByte(0xff);

      //---------------------------------
      // Image Descriptor

      out.writeString(',');
      out.writeShort(0);
      out.writeShort(0);
      out.writeShort(_width);
      out.writeShort(_height);
      out.writeByte(0);

      //---------------------------------
      // Local Color Map

      //---------------------------------
      // Raster Data

      var lzwMinCodeSize = 2;
      var raster = getLZWRaster(lzwMinCodeSize);

      out.writeByte(lzwMinCodeSize);

      var offset = 0;

      while (raster.length - offset > 255) {
        out.writeByte(255);
        out.writeBytes(raster, offset, 255);
        offset += 255;
      }

      out.writeByte(raster.length - offset);
      out.writeBytes(raster, offset, raster.length - offset);
      out.writeByte(0x00);

      //---------------------------------
      // GIF Terminator
      out.writeString(';');
    };

    var bitOutputStream = function(out) {

      var _out = out;
      var _bitLength = 0;
      var _bitBuffer = 0;

      var _this = {};

      _this.write = function(data, length) {

        if ( (data >>> length) != 0) {
          throw 'length over';
        }

        while (_bitLength + length >= 8) {
          _out.writeByte(0xff & ( (data << _bitLength) | _bitBuffer) );
          length -= (8 - _bitLength);
          data >>>= (8 - _bitLength);
          _bitBuffer = 0;
          _bitLength = 0;
        }

        _bitBuffer = (data << _bitLength) | _bitBuffer;
        _bitLength = _bitLength + length;
      };

      _this.flush = function() {
        if (_bitLength > 0) {
          _out.writeByte(_bitBuffer);
        }
      };

      return _this;
    };

    var getLZWRaster = function(lzwMinCodeSize) {

      var clearCode = 1 << lzwMinCodeSize;
      var endCode = (1 << lzwMinCodeSize) + 1;
      var bitLength = lzwMinCodeSize + 1;

      // Setup LZWTable
      var table = lzwTable();

      for (var i = 0; i < clearCode; i += 1) {
        table.add(String.fromCharCode(i) );
      }
      table.add(String.fromCharCode(clearCode) );
      table.add(String.fromCharCode(endCode) );

      var byteOut = byteArrayOutputStream();
      var bitOut = bitOutputStream(byteOut);

      // clear code
      bitOut.write(clearCode, bitLength);

      var dataIndex = 0;

      var s = String.fromCharCode(_data[dataIndex]);
      dataIndex += 1;

      while (dataIndex < _data.length) {

        var c = String.fromCharCode(_data[dataIndex]);
        dataIndex += 1;

        if (table.contains(s + c) ) {

          s = s + c;

        } else {

          bitOut.write(table.indexOf(s), bitLength);

          if (table.size() < 0xfff) {

            if (table.size() == (1 << bitLength) ) {
              bitLength += 1;
            }

            table.add(s + c);
          }

          s = c;
        }
      }

      bitOut.write(table.indexOf(s), bitLength);

      // end code
      bitOut.write(endCode, bitLength);

      bitOut.flush();

      return byteOut.toByteArray();
    };

    var lzwTable = function() {

      var _map = {};
      var _size = 0;

      var _this = {};

      _this.add = function(key) {
        if (_this.contains(key) ) {
          throw 'dup key:' + key;
        }
        _map[key] = _size;
        _size += 1;
      };

      _this.size = function() {
        return _size;
      };

      _this.indexOf = function(key) {
        return _map[key];
      };

      _this.contains = function(key) {
        return typeof _map[key] != 'undefined';
      };

      return _this;
    };

    return _this;
  };

  var createDataURL = function(width, height, getPixel) {
    var gif = gifImage(width, height);
    for (var y = 0; y < height; y += 1) {
      for (var x = 0; x < width; x += 1) {
        gif.setPixel(x, y, getPixel(x, y) );
      }
    }

    var b = byteArrayOutputStream();
    gif.write(b);

    var base64 = base64EncodeOutputStream();
    var bytes = b.toByteArray();
    for (var i = 0; i < bytes.length; i += 1) {
      base64.writeByte(bytes[i]);
    }
    base64.flush();

    return 'data:image/gif;base64,' + base64;
  };

  //---------------------------------------------------------------------
  // returns qrcode function.

  return qrcode;
}();

// multibyte support
!function() {

  qrcode.stringToBytesFuncs['UTF-8'] = function(s) {
    // http://stackoverflow.com/questions/18729405/how-to-convert-utf8-string-to-byte-array
    function toUTF8Array(str) {
      var utf8 = [];
      for (var i=0; i < str.length; i++) {
        var charcode = str.charCodeAt(i);
        if (charcode < 0x80) utf8.push(charcode);
        else if (charcode < 0x800) {
          utf8.push(0xc0 | (charcode >> 6),
              0x80 | (charcode & 0x3f));
        }
        else if (charcode < 0xd800 || charcode >= 0xe000) {
          utf8.push(0xe0 | (charcode >> 12),
              0x80 | ((charcode>>6) & 0x3f),
              0x80 | (charcode & 0x3f));
        }
        // surrogate pair
        else {
          i++;
          // UTF-16 encodes 0x10000-0x10FFFF by
          // subtracting 0x10000 and splitting the
          // 20 bits of 0x0-0xFFFFF into two halves
          charcode = 0x10000 + (((charcode & 0x3ff)<<10)
            | (str.charCodeAt(i) & 0x3ff));
          utf8.push(0xf0 | (charcode >>18),
              0x80 | ((charcode>>12) & 0x3f),
              0x80 | ((charcode>>6) & 0x3f),
              0x80 | (charcode & 0x3f));
        }
      }
      return utf8;
    }
    return toUTF8Array(s);
  };

}();

(function (factory) {
  if (typeof define === 'function' && define.amd) {
      define([], factory);
  } else if (typeof exports === 'object') {
      module.exports = factory();
  }
}(function () {
    return qrcode;
}));

</script>
<script>
(()=>{
const $=(s,r=document)=>r.querySelector(s);
const el=(t,c,x)=>{const e=document.createElement(t);if(c)e.className=c;if(x!=null)e.textContent=x;return e};
const rp=n=>'Rp '+n.toLocaleString('id-ID');
const DB_TRIPS = @json($tripsData ?? []);
const FALLBACK_TRIPS = [
{id:1,from:'Jember',to:'Surabaya',dep:'06.00',arr:'11.00',v:'hiace',price:150000,taken:[2,5,6]},
{id:2,from:'Jember',to:'Surabaya',dep:'13.00',arr:'18.00',v:'hiace',price:150000,taken:[1,3,4,7,8,9,11]},
{id:3,from:'Jember',to:'Malang',dep:'07.30',arr:'11.30',v:'innova',price:130000,taken:[4]},
{id:4,from:'Jember',to:'Banyuwangi',dep:'09.00',arr:'12.00',v:'elf',price:100000,taken:[1,2,9]},
{id:5,from:'Surabaya',to:'Jember',dep:'15.00',arr:'20.00',v:'hiace',price:150000,taken:[10]},
{id:6,from:'Jember',to:'Malang',dep:'17.30',arr:'21.30',v:'innova',price:130000,taken:[2,3]},
{id:7,from:'Malang',to:'Jember',dep:'21.00',arr:'01.00',v:'hiace',price:130000,taken:[1]}];
const TRIPS = (DB_TRIPS && DB_TRIPS.length > 0) ? DB_TRIPS : FALLBACK_TRIPS;
const VEH={
innova:{name:'Innova Reborn',n:6,fac:'AC \u00b7 Kursi captain \u00b7 Bagasi',rows:[['D','.',1],[2,'.',3],[4,5,6]]},
hiace:{name:'Hiace Commuter',n:14,fac:'AC \u00b7 Colokan \u00b7 Bagasi',rows:[['D',1,'.',2],[3,4,'.',5],[6,7,'.',8],[9,10,'.',11],[12,13,'.',14]]},
elf:{name:'Isuzu Elf Long',n:17,fac:'AC \u00b7 Bagasi besar',rows:[['D',1,'.',2],[3,4,'.',5],[6,7,'.',8],[9,10,'.',11],[12,13,'.',14],[15,16,'.',17]]}};
const MAX=3;
const AUTH_USER = @json($authUser ?? null);
const S={
  user: AUTH_USER ? {
    name: AUTH_USER.name,
    email: AUTH_USER.email,
    phone: AUTH_USER.phone || '',
    role: AUTH_USER.role || 'passenger',
    isAdmin: AUTH_USER.isAdmin || false
  } : null,
  trip:null,sel:new Set(),next:null,orders:[],cur:null,timer:0
};
const ST={pending:['Menunggu pembayaran',''],waiting_verification:['Menunggu verifikasi','b2'],confirmed:['Terkonfirmasi','b3'],cancelled:['Dibatalkan','b4'],expired:['Kedaluwarsa','b4']};

let tt=0;
function toast(m){const t=$('#toast');t.textContent=m;t.classList.add('on');clearTimeout(tt);tt=setTimeout(()=>t.classList.remove('on'),2600)}
function dateLabel(){const v=$('#qd').value;const d=v?new Date(v+'T00:00:00'):new Date();return d.toLocaleDateString('id-ID',{weekday:'short',day:'numeric',month:'short'})}

[...new Set(TRIPS.flatMap(t=>[t.from,t.to]))].sort().forEach(c=>{for(const id of['#qf','#qt']){const o=el('option',null,c);o.value=c;$(id).append(o)}});
const tm=new Date();tm.setDate(tm.getDate()+1);$('#qd').value=tm.toISOString().slice(0,10);$('#qd').min=new Date().toISOString().slice(0,10);

function renderAuth(){
  const a=$('#auth');a.replaceChildren();
  if(S.user){
    const pf=el('button','pill','Halo, '+S.user.name);
    pf.type='button';pf.title='Profil saya';pf.onclick=showProfile;
    a.append(pf);
    if(S.user.isAdmin){
      const adm=el('a','btn alt','Admin');
      adm.href='{{ route("admin.dashboard") }}';
      adm.style.textDecoration='none';
      adm.style.display='inline-flex';
      adm.style.alignItems='center';
      a.append(adm);
    }
    const b=el('button','btn alt','Keluar');
    b.type='button';
    b.onclick=()=>{
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("logout") }}';
      const token = document.createElement('input');
      token.type = 'hidden';
      token.name = '_token';
      token.value = '{{ csrf_token() }}';
      form.appendChild(token);
      document.body.appendChild(form);
      form.submit();
    };
    a.append(b);
  } else {
    const l=el('button','btn alt','Masuk');l.type='button';l.onclick=()=>openGate('login');
    const d=el('button','btn','Daftar');d.type='button';d.onclick=()=>openGate('daftar');
    a.append(l,d);
  }
  const gn = $('#guestNote');
  if(gn) gn.hidden=!!S.user;
}

function renderTrips(){
  const f=$('#qf').value,t=$('#qt').value,box=$('#trips');box.replaceChildren();
  const list=TRIPS.filter(x=>(!f||x.from===f)&&(!t||x.to===t)&&inSlot(x));
  box.append(el('p','mut',list.length?list.length+' jadwal pada '+dateLabel():'Tidak ada jadwal untuk pilihan ini. Coba ubah kota atau tanggal.'));
  list.forEach(x=>{
    const V=VEH[x.v],left=V.n-x.taken.length;
    const c=el('article','card trip');
    const a=el('div','tm',x.dep);a.append(el('small',null,'tiba '+x.arr));
    const m=el('div');m.append(el('strong',null,x.from+' → '+x.to));m.append(el('div','mut small',V.name+' · '+V.n+' kursi · '+dateLabel()));
    const pl=el('span','pill',left?'Sisa '+left+' kursi':'Penuh');m.append(pl);
    const p=el('div','pr');p.append(el('strong',null,rp(x.price)));
    const b=el('button','btn','Lihat kursi');b.type='button';b.disabled=!left;b.onclick=()=>openTrip(x);
    const w=el('div');w.append(b);p.append(el('br'),w);
    c.append(a,m,p);box.append(c);
  });
}
$('#cari').addEventListener('submit',e=>{e.preventDefault();renderTrips();go('jadwal')});

function openTrip(t){
  S.trip=t;S.sel.clear();
  $('#tTitle').textContent=t.from+' → '+t.to;$('#tMeta').textContent=dateLabel()+' · '+t.dep+' · '+VEH[t.v].name+' · '+VEH[t.v].n+' kursi · '+VEH[t.v].fac+' (tata letak contoh)';
  const g=$('#seatBtns');g.replaceChildren();
  const L=VEH[t.v].rows;g.style.gridTemplateColumns='repeat('+L[0].length+',1fr)';g.style.maxWidth=(L[0].length*64)+'px';
  const cells=L.flat();
  cells.forEach(v=>{
    if(v==='D'){g.append(el('div','drv','Sopir'));return}
    if(v==='.'){g.append(el('span'));return}
    const b=el('button','seat',String(v));b.type='button';b.dataset.id=v;
    const tk=t.taken.includes(v);b.disabled=tk;b.setAttribute('aria-label','Kursi '+v+(tk?', terisi':''));
    b.onclick=()=>setSeat(v);g.append(b);
  });
  go('kursi');init3D();if(R)R.build(VEH[t.v]);refresh();
}
function setSeat(id){
  if(!S.trip||S.trip.taken.includes(id))return;
  if(S.sel.has(id))S.sel.delete(id);
  else if(S.sel.size>=MAX){toast('Maksimal '+MAX+' kursi per pemesanan');return}
  else S.sel.add(id);
  refresh();
}
function refresh(){
  if(!S.trip)return;
  document.querySelectorAll('#seatBtns .seat').forEach(b=>b.setAttribute('aria-pressed',S.sel.has(+b.dataset.id)));
  const n=S.sel.size;
  $('#picked').textContent=n?'Kursi '+[...S.sel].sort((a,b)=>a-b).join(', '):'Belum ada kursi dipilih';
  $('#total').textContent=rp(n*S.trip.price);
  $('#goBook').disabled=!n;
  $('#goBook').textContent=S.user?'Lanjut pesan':'Masuk untuk memesan';
  $('#gateHint').textContent=S.user?'':'Kamu masih mode tamu. Memilih kursi boleh, tetapi memesan memerlukan akun.';
  paint();
}
$('#goBook').onclick=()=>{
  requireLogin(()=>{
    if(S.trip && S.trip.id && DB_TRIPS.some(t => t.id === S.trip.id)){
      window.location.href = '{{ url("/jadwal") }}/' + S.trip.id + '/pesan';
    } else {
      showBook();
    }
  });
};
const noBtn = $('#navOrd');
if(noBtn) {
  noBtn.onclick=()=>{
    if(S.user){
      window.location.href = '{{ route("pemesanan.index") }}';
    } else {
      requireLogin(()=>{
        window.location.href = '{{ route("pemesanan.index") }}';
      });
    }
  };
}
$('#gn1').onclick=()=>openGate('login');

const G=$('#gate');let mode='login';
function openGate(m){setMode(m);$('#gerr').textContent='';G.showModal();$('#ge').focus()}
function setMode(m){
  mode=m;$('#gf').classList.toggle('login',m==='login');
  $('#tl').setAttribute('aria-selected',m==='login');$('#td').setAttribute('aria-selected',m!=='login');
  $('#gt').textContent=m==='login'?'Masuk untuk memesan':'Buat akun penumpang';
  $('#gs').textContent=m==='login'?'Masuk':'Daftar';
  $('#gpw').autocomplete=m==='login'?'current-password':'new-password';
}
$('#tl').onclick=()=>setMode('login');$('#td').onclick=()=>setMode('daftar');
$('#gx').onclick=()=>{S.next=null;G.close()};
G.addEventListener('cancel',()=>{S.next=null});
function requireLogin(cb){if(S.user)cb();else{S.next=cb;openGate('login')}}
function finishLogin(name){
  S.user={name,email:$('#ge').value.trim()||'penumpang@contoh.id',phone:$('#gph').value.trim()};G.close();renderAuth();refresh();toast('Selamat datang, '+name);
  const n=S.next;S.next=null;if(n)n();
}
$('#gf').addEventListener('submit', async (e) => {
  e.preventDefault();
  const em=$('#ge').value.trim(), pw=$('#gpw').value, nm=$('#gn').value.trim(), ph=$('#gph').value.trim();
  let err='';
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)) err='Masukkan alamat email yang valid.';
  else if(pw.length<8) err='Kata sandi minimal 8 karakter.';
  else if(mode==='daftar'&&nm.length<2) err='Nama lengkap minimal 2 karakter.';
  else if(mode==='daftar'&&!/^(\+62|0)8\d{8,12}$/.test(ph)) err='Nomor telepon tidak valid. Contoh: 081234567890.';
  $('#gerr').textContent=err;
  if(err) return;

  $('#gs').disabled = true;
  $('#gs').textContent = 'Memproses...';

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
  const url = mode === 'login' ? '{{ route("login.attempt") }}' : '{{ route("register.attempt") }}';
  const payload = mode === 'login'
    ? { email: em, password: pw }
    : { name: nm, email: em, phone: ph, password: pw, password_confirmation: pw };

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify(payload)
    });

    if (res.ok) {
      window.location.reload();
      return;
    } else {
      const data = await res.json().catch(() => null);
      if (data && data.errors) {
        const firstErr = Object.values(data.errors).flat()[0];
        $('#gerr').textContent = firstErr || 'Gagal memproses data.';
      } else if (data && data.message) {
        $('#gerr').textContent = data.message;
      } else {
        const f = $('#gf');
        f.action = url;
        f.method = 'POST';
        f.submit();
        return;
      }
    }
  } catch (ex) {
    const f = $('#gf');
    f.action = url;
    f.method = 'POST';
    f.submit();
    return;
  } finally {
    $('#gs').disabled = false;
    $('#gs').textContent = mode === 'login' ? 'Masuk' : 'Daftar';
  }
});
$('#gg').onclick=()=>finishLogin('Penumpang');

function showBook(){
  const v=$('#bookView');v.replaceChildren();go('pesan');
  const t=S.trip,ids=[...S.sel].sort((a,b)=>a-b);
  const c=el('div','card');const h=el('h2',null,'Data penumpang');h.style.fontSize='26px';c.append(h);
  c.append(el('p','mut',t.from+' \u2192 '+t.to+' \u00b7 '+dateLabel()+' \u00b7 '+t.dep+' \u00b7 '+VEH[t.v].name+' \u00b7 kursi '+ids.join(', ')+' \u00b7 '+rp(ids.length*t.price)));
  ids.forEach(id=>{
    const r=el('div','row');
    const a=el('label','f','Nama penumpang, kursi '+id);const ia=el('input');ia.maxLength=60;ia.autocomplete='name';ia.dataset.k='n';a.append(ia);
    const b=el('label','f','Nomor telepon');const ib=el('input');ib.type='tel';ib.maxLength=20;ib.autocomplete='tel';ib.dataset.k='p';b.append(ib);
    r.append(a,b);c.append(r);
  });
  const mh=el('h3',null,'Metode pembayaran');mh.style.cssText='font-size:20px;margin:16px 0 8px';c.append(mh);
  const pr=el('div','row');
  [['qris_manual','QRIS'],['bank_transfer_manual','Transfer bank']].forEach(([val,lab],i)=>{
    const o=el('label','opt');const inp=el('input');inp.type='radio';inp.name='pay';inp.value=val;inp.checked=!i;o.append(inp,document.createTextNode(lab));pr.append(o);
  });
  c.append(pr);
  const er=el('p','err');er.setAttribute('role','alert');c.append(er);
  const mkb=el('button','btn','Buat pesanan');mkb.type='button';
  const bk=el('button','btn alt','Kembali pilih kursi');bk.type='button';bk.style.marginLeft='10px';bk.onclick=()=>go('kursi');
  c.append(mkb,bk,el('p','mut small','Kursi ditahan 2 jam setelah pesanan dibuat. Bukti pembayaran diperiksa admin sebelum e-ticket terbit.'));
  v.append(c);
  mkb.onclick=()=>{
    const rows=[...c.querySelectorAll('.row')].slice(0,ids.length);let ok=true;
    const pax=rows.map((r,i)=>{
      const n=$('[data-k=n]',r).value.trim(),p=$('[data-k=p]',r).value.trim();
      if(n.length<2||!/^(\+62|0)8\d{8,12}$/.test(p))ok=false;
      return {seat:ids[i],name:n,phone:p};
    });
    if(!ok){er.textContent='Lengkapi nama (minimal 2 karakter) dan nomor telepon yang valid untuk setiap penumpang.';return}
    er.textContent='';
    const code='LGO-'+Math.random().toString(36).slice(2,8).toUpperCase();
    const o={code,trip:t,date:dateLabel(),at:DEP(t),pax,method:$('input[name=pay]:checked',c).value,status:'pending',deadline:Date.now()+7200e3,total:pax.length*t.price,note:'',paid:false};
    S.orders.push(o);t.taken.push(...ids);S.sel.clear();refresh();openOrder(o);toast('Pesanan dibuat. Kursi ditahan 2 jam.');
  };
}

function fakeQr(seed){
  const q=el('div','qr');let h=0;for(const ch of seed)h=(h*31+ch.charCodeAt(0))>>>0;
  const fin=(x,y)=>(x<3&&y<3)||(x>7&&y<3)||(x<3&&y>7);
  for(let y=0;y<11;y++)for(let x=0;x<11;x++){h=(h*1664525+1013904223)>>>0;q.append(el('i',(fin(x,y)||(h>>>28)&1)?'':'o'))}
  return q;
}
function qrNode(text){
  if(typeof qrcode!=='function')return fakeQr(text);
  const q=qrcode(0,'M');q.addData(text);q.make();
  const n=q.getModuleCount(),m=2,ns='http://www.w3.org/2000/svg',s=document.createElementNS(ns,'svg');
  s.setAttribute('viewBox','0 0 '+(n+2*m)+' '+(n+2*m));s.setAttribute('class','qrs');s.setAttribute('role','img');s.setAttribute('aria-label','Kode QR tiket '+text);s.setAttribute('shape-rendering','crispEdges');
  const bg=document.createElementNS(ns,'rect');bg.setAttribute('width',n+2*m);bg.setAttribute('height',n+2*m);bg.setAttribute('fill','#fff');
  let d='';for(let r=0;r<n;r++)for(let c=0;c<n;c++)if(q.isDark(r,c))d+='M'+(c+m)+' '+(r+m)+'h1v1h-1z';
  const pa=document.createElementNS(ns,'path');pa.setAttribute('d',d);pa.setAttribute('fill','#2A1418');
  s.append(bg,pa);return s;
}
const DEP=t=>new Date(($('#qd').value||new Date().toISOString().slice(0,10))+'T'+t.dep.replace('.',':')+':00');
const tcode=(o,p)=>'TKT-'+o.code.slice(4)+'-'+p.seat;
const canCancel=o=>['pending','waiting_verification','confirmed'].includes(o.status)&&o.at-Date.now()>=6*3600e3;
function release(o){o.trip.taken=o.trip.taken.filter(id=>!o.pax.some(p=>p.seat===id));refresh()}
function expire(o){o.status='expired';release(o)}
const badge=st=>el('span','badge '+ST[st][1],ST[st][0]);

function renderOrders(){
  const v=$('#ordersView');v.replaceChildren();
  const sh=el('div','sh');sh.append(el('h2',null,'Pesanan saya'));v.append(sh);
  if(!S.orders.length){
    const c=el('div','card');c.append(el('p',null,'Belum ada pesanan. Pilih jadwal dan kursi untuk memulai.'));
    const b=el('button','btn','Cari jadwal');b.type='button';b.onclick=()=>go('jadwal');c.append(b);v.append(c);return;
  }
  [...S.orders].reverse().forEach(o=>{
    const c=el('article','card trip');
    const a=el('div','tm',o.code);a.append(el('small',null,o.date+' \u00b7 '+o.trip.dep));
    const m=el('div');m.append(el('strong',null,o.trip.from+' \u2192 '+o.trip.to),el('div','mut small','Kursi '+o.pax.map(p=>p.seat).join(', ')+' \u00b7 '+rp(o.total)));
    const r=el('div','pr');const b=el('button','btn','Lihat detail');b.type='button';b.onclick=()=>openOrder(o);
    r.append(badge(o.status),el('br'),b);c.append(a,m,r);v.append(c);
  });
}

function openOrder(o){
  S.cur=o;clearInterval(S.timer);
  const v=$('#orderView');v.replaceChildren();
  const c=el('div','card');
  const top=el('div');top.style.cssText='display:flex;gap:12px;align-items:center;flex-wrap:wrap';
  const bk=el('button','btn alt','\u2190 Semua pesanan');bk.type='button';bk.onclick=()=>{renderOrders();go('pesanan')};
  const h=el('h2',null,'Pesanan '+o.code);h.style.fontSize='26px';top.append(bk,h,badge(o.status));c.append(top);
  c.append(el('p','mut',o.trip.from+' \u2192 '+o.trip.to+' \u00b7 '+o.date+' \u00b7 '+o.trip.dep+' \u00b7 '+VEH[o.trip.v].name+' \u00b7 kursi '+o.pax.map(p=>p.seat).join(', ')+' \u00b7 '+rp(o.total)+' \u00b7 '+(o.method==='qris_manual'?'QRIS':'Transfer bank')));
  const step=({pending:1,waiting_verification:2,confirmed:3})[o.status]||0;
  const tl=el('ol','tl');['Pesanan dibuat','Bukti dikirim','Terkonfirmasi'].forEach((x,i)=>tl.append(el('li',step>i?'on':'',x)));c.append(tl);
  const demo=el('div','demo');demo.append(el('small','mut','Simulasi peran admin (hanya di prototipe):'));
  const dbtn=(t,fn)=>{const b=el('button','btn alt',t);b.type='button';b.onclick=fn;demo.append(b)};
  if(o.status==='pending'){
    if(o.note){const n=el('p','err','Pembayaran ditolak: '+o.note+'. Unggah bukti yang baru.');n.setAttribute('role','alert');c.append(n)}
    const cd=el('p','badge');c.append(cd);
    c.append(el('p',null,o.method==='qris_manual'?'Bayar dengan memindai QRIS travel (contoh), lalu unggah bukti pembayaran.':'Transfer ke Bank Contoh 1234567890 a.n. PT LintasGo Travel (contoh), lalu unggah bukti pembayaran.'));
    const f=el('label','f','Bukti pembayaran (JPG, PNG, atau PDF, maksimal 2 MB)');const fi=el('input');fi.type='file';fi.accept='.jpg,.jpeg,.png,.pdf';f.append(fi);c.append(f);
    const er=el('p','err');er.setAttribute('role','alert');c.append(er);
    fi.onchange=()=>{
      const x=fi.files[0];if(!x)return;
      if(!['image/jpeg','image/png','application/pdf'].includes(x.type)){er.textContent='Jenis berkas tidak diizinkan. Gunakan JPG, PNG, atau PDF.';fi.value='';return}
      if(x.size>2*1024*1024){er.textContent='Ukuran berkas melebihi 2 MB.';fi.value='';return}
      o.status='waiting_verification';o.note='';openOrder(o);toast('Bukti terkirim. Menunggu verifikasi admin.');
    };
    dbtn('Waktu bayar habis',()=>{expire(o);openOrder(o)});
    v.append(c);go('detail');
    const tick=()=>{
      const sec=Math.max(0,Math.floor((o.deadline-Date.now())/1000));
      if(!sec){clearInterval(S.timer);expire(o);openOrder(o);toast('Waktu pembayaran habis. Kursi dilepas.');return}
      const m=Math.floor(sec/60);cd.textContent='Bayar dalam '+Math.floor(m/60)+' jam '+(m%60)+' menit '+(sec%60)+' detik';
    };
    tick();S.timer=setInterval(tick,1000);
  }else v.append(c);
  if(o.status==='waiting_verification'){
    c.append(el('p',null,'Admin sedang memeriksa bukti pembayaranmu. E-ticket terbit di sini setelah disetujui.'));
    dbtn('Admin menyetujui',()=>{o.status='confirmed';o.paid=true;openOrder(o);toast('Pembayaran disetujui. E-ticket terbit.')});
    dbtn('Admin menolak',()=>{o.status='pending';o.note='bukti kurang jelas';o.deadline=Date.now()+3600e3;openOrder(o)});
  }
  if(o.status==='confirmed'){
    c.append(el('h3',null,'E-ticket'));c.lastChild.style.cssText='font-size:22px;margin:14px 0 4px';
    o.pax.forEach(p=>{
      const t=el('div','tk');const l=el('div','l');l.append(el('small',null,'KURSI'),el('br'),el('b',null,String(p.seat)));
      const m=el('div','m');m.append(el('strong',null,p.name),el('div','mut small',tcode(o,p)),el('div','small',p.checked?'Sudah check-in':'Tiket aktif'));
      const b=el('button','btn','Lihat e-ticket');b.type='button';b.style.margin='10px';b.style.alignSelf='center';b.onclick=()=>openTicket(o,p);
      t.append(l,m,b);c.append(t);
    });
    dbtn('Admin check-in semua penumpang',()=>{o.pax.forEach(p=>p.checked=true);openOrder(o);toast('Check-in tercatat.')});
  }
  if(o.status==='cancelled')c.append(el('p',null,'Pesanan dibatalkan dan kursi sudah dilepas.'+(o.paid?' Pengembalian dana diproses admin di luar sistem.':'')));
  if(o.status==='expired')c.append(el('p',null,'Batas waktu pembayaran terlewat, jadi kursi dilepas. Silakan pesan lagi.'));
  if(['pending','waiting_verification','confirmed'].includes(o.status)){
    const row=el('div');row.style.marginTop='16px';
    const cb=el('button','btn alt','Batalkan pesanan');cb.type='button';cb.disabled=!canCancel(o);
    let arm=0;cb.onclick=()=>{
      if(!arm){arm=1;cb.textContent='Yakin batalkan? Klik lagi';setTimeout(()=>{arm=0;cb.textContent='Batalkan pesanan'},4000);return}
      o.paid=o.status!=='pending';o.status='cancelled';release(o);openOrder(o);toast('Pesanan dibatalkan. Kursi dilepas.');
    };
    row.append(cb,el('p','mut small','Pembatalan hanya bisa sampai 6 jam sebelum keberangkatan.'));c.append(row);
  }
  if(demo.children.length>1)c.append(demo);
  go('detail');
}

function openTicket(o,p){
  S.cur=o;const v=$('#ticketView');v.replaceChildren();
  const tb=el('div','noprint');tb.style.cssText='display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px';
  const bk=el('button','btn alt','\u2190 Kembali ke pesanan');bk.type='button';bk.onclick=()=>openOrder(o);
  const pr=el('button','btn','Cetak e-ticket');pr.type='button';pr.onclick=()=>{try{window.print()}catch(e){toast('Cetak tidak tersedia di pratinjau ini')}};
  tb.append(bk,pr);v.append(tb);
  const c=el('div','card etk');
  const eh=el('div','eh');eh.append(el('span',null,'LintasGo'),el('span','badge '+(p.checked?'b3':'b2'),p.checked?'Sudah check-in':'Tiket aktif'));
  const eb=el('div','eb');const inf=el('div');
  inf.append(el('small','mut','RUTE'));
  const rt=el('h3',null,o.trip.from+' \u2192 '+o.trip.to);rt.style.fontSize='32px';inf.append(rt);
  const dl=el('dl');
  [['Tanggal',o.date],['Berangkat',o.trip.dep],['Tiba',o.trip.arr],['Kendaraan',VEH[o.trip.v].name],['Kursi',String(p.seat)],['Penumpang',p.name],['Kode pesanan',o.code],['Kode tiket',tcode(o,p)]].forEach(([k,val])=>{dl.append(el('dt',null,k),el('dd',null,val))});
  inf.append(dl);
  const qb=el('div');qb.style.textAlign='center';qb.append(qrNode(tcode(o,p)),el('p','mut small','Tunjukkan QR ini ke petugas saat check-in'));
  eb.append(inf,qb);c.append(eh,eb);v.append(c);
  go('tiket');
}

function showProfile(){
  const v=$('#profView');v.replaceChildren();
  const c=el('div','card');const h=el('h2',null,'Profil penumpang');h.style.fontSize='26px';c.append(h);
  const fld=(lab,val,type,ac)=>{const l=el('label','f',lab);const i=el('input');i.type=type||'text';i.value=val||'';i.autocomplete=ac||'off';i.maxLength=100;l.append(i);c.append(l);return i};
  const n=fld('Nama lengkap',S.user.name,'text','name'),e=fld('Email',S.user.email,'email','email'),p=fld('Nomor telepon',S.user.phone,'tel','tel');e.readOnly=true;
  const er=el('p','err');er.setAttribute('role','alert');c.append(er);
  const sv=el('button','btn','Simpan profil');sv.type='button';
  sv.onclick=()=>{
    if(n.value.trim().length<2){er.textContent='Nama minimal 2 karakter.';return}
    if(p.value.trim()&&!/^(\+62|0)8\d{8,12}$/.test(p.value.trim())){er.textContent='Nomor telepon tidak valid.';return}
    er.textContent='';S.user.name=n.value.trim();S.user.phone=p.value.trim();renderAuth();toast('Profil disimpan');
  };
  c.append(sv);
  const h2=el('h3',null,'Ganti kata sandi');h2.style.cssText='font-size:20px;margin:22px 0 4px';c.append(h2);
  const cu=fld('Kata sandi saat ini','','password','current-password'),nw=fld('Kata sandi baru (minimal 8 karakter)','','password','new-password'),cf=fld('Ulangi kata sandi baru','','password','new-password');
  const er2=el('p','err');er2.setAttribute('role','alert');c.append(er2);
  const pw=el('button','btn alt','Ganti kata sandi');pw.type='button';
  pw.onclick=()=>{
    if(!cu.value||nw.value.length<8)er2.textContent='Isi kata sandi saat ini dan kata sandi baru minimal 8 karakter.';
    else if(nw.value!==cf.value)er2.textContent='Konfirmasi kata sandi tidak sama.';
    else{er2.textContent='';cu.value=nw.value=cf.value='';toast('Kata sandi diganti (simulasi)')}
  };
  c.append(pw);v.append(c);go('profil');
}

let R=null;
function init3D(){
  if(R)return;const box=$('#stage');
  if(!window.THREE){box.append(el('p','mut','3D tidak dapat dimuat. Gunakan tombol kursi di samping.'));return}
  try{
    const T=THREE,sc=new T.Scene(),cam=new T.PerspectiveCamera(34,1,.1,100);
    const rd=new T.WebGLRenderer({antialias:true,alpha:true});rd.setPixelRatio(Math.min(devicePixelRatio,2));box.append(rd.domElement);
    sc.add(new T.HemisphereLight(0xffffff,0x7a1f2b,.9));const dl=new T.DirectionalLight(0xffffff,.75);dl.position.set(4,9,5);sc.add(dl);
    const g=new T.Group();sc.add(g);g.rotation.set(.05,-.35,0);
    const mt=c=>new T.MeshStandardMaterial({color:c,roughness:.75});
    const bx=(w,h,d,c,x,y,z,p)=>{const m=new T.Mesh(new T.BoxGeometry(w,h,d),mt(c));m.position.set(x,y,z);(p||g).add(m);return m};
    let seats=[],picks=[],raf=0;
    const k=matchMedia('(prefers-reduced-motion:reduce)').matches?1:.25;
    const need=()=>{if(!raf)raf=requestAnimationFrame(frame)};
    function frame(){raf=0;let mv=false;seats.forEach(o=>{const d=o.ty-o.s.position.y;if(Math.abs(d)>.003){o.s.position.y+=d*k;mv=true}});rd.render(sc,cam);if(mv)need()}
    const fit=()=>{const w=box.clientWidth,h=box.clientHeight;if(!w||!h)return;rd.setSize(w,h);cam.aspect=w/h;cam.updateProjectionMatrix();need()};
    new ResizeObserver(fit).observe(box);
    let dn=null,mv=0;const ray=new T.Raycaster(),mp=new T.Vector2(),cv=rd.domElement;
    cv.addEventListener('pointerdown',e=>{dn={x:e.clientX,y:e.clientY};mv=0;cv.setPointerCapture(e.pointerId);box.style.cursor='grabbing'});
    cv.addEventListener('pointermove',e=>{if(!dn)return;const dx=e.clientX-dn.x,dy=e.clientY-dn.y;mv+=Math.abs(dx)+Math.abs(dy);dn={x:e.clientX,y:e.clientY};g.rotation.y=Math.max(-1.1,Math.min(1.1,g.rotation.y+dx*.008));g.rotation.x=Math.max(-.1,Math.min(.45,g.rotation.x+dy*.004));need()});
    cv.addEventListener('pointerup',e=>{
      box.style.cursor='grab';if(!dn)return;dn=null;if(mv>6)return;
      const r=cv.getBoundingClientRect();mp.set(((e.clientX-r.left)/r.width)*2-1,-((e.clientY-r.top)/r.height)*2+1);
      ray.setFromCamera(mp,cam);const h=ray.intersectObjects(picks,false)[0];if(h)setSeat(h.object.userData.id);
    });
    const mk=(x,z,col)=>{const s=new T.Group();s.position.set(x,0,z);g.add(s);const a=bx(1.25,.45,1.25,col,0,.22,0,s),b=bx(1.25,1,.28,col,0,.85,.55,s);return {s,ms:[a.material,b.material],pick:[a,b]}};
    R={
      build(v){
        g.clear();seats=[];picks=[];
        const rows=v.rows,cols=rows[0].length,sx=1.7,sz=1.75,W=cols*sx+.9,L=rows.length*sz+1.2,f=Math.max(W,L)/7.4;
        cam.position.set(0,8.4*f,9.8*f);cam.lookAt(0,0,.2);
        bx(W,.25,L,0xCDB794,0,-.15,0);bx(.2,1.5,L,0x7A1F2B,-W/2,.6,0);bx(.2,1.5,L,0x7A1F2B,W/2,.6,0);bx(W,1.1,.4,0x2A5DA8,0,.4,-L/2);
        rows.forEach((row,r)=>row.forEach((id,c)=>{
          if(id==='.')return;
          const x=(c-(cols-1)/2)*sx,z=(r-(rows.length-1)/2)*sz;
          if(id==='D'){mk(x,z,0x3B2A2E);return}
          const o=mk(x,z,0xFFF4DC);o.id=id;o.ty=0;o.pick.forEach(m=>{m.userData.id=id;picks.push(m)});seats.push(o);
        }));
        need();
      },
      paint(){seats.forEach(o=>{const tk=S.trip.taken.includes(o.id),sl=S.sel.has(o.id);o.ms.forEach(m=>m.color.setHex(tk?0x8E7F82:sl?0x2A5DA8:0xFFF4DC));o.ty=sl?.2:0});need()}
    };
  }catch(e){box.append(el('p','mut','3D tidak didukung di perangkat ini. Gunakan tombol kursi di samping.'))}
}
function paint(){if(R)R.paint()}

const SL=[[4,8,'Subuh Semangat','☾'],[8,12,'Pagi Cerah','☀'],[12,16,'Siang Santuy','☼'],[16,20,'Sore Syahdu','☁'],[20,24,'Malam Meriah','✦'],[0,4,'Tengah Malam','☾']];
const hr=t=>parseInt(t.dep,10);
const inSlot=t=>S.slot==null||(hr(t)>=SL[S.slot][0]&&hr(t)<SL[S.slot][1]);
function renderSlots(){
  const g=$('#slots'),p=x=>String(x%24).padStart(2,'0');g.replaceChildren();
  SL.forEach((s,i)=>{
    const n=TRIPS.filter(t=>hr(t)>=s[0]&&hr(t)<s[1]).length;
    const b=el('button','slot');b.type='button';b.setAttribute('aria-pressed',S.slot===i);
    b.append(el('em',null,s[3]+' '+s[2]),el('b',null,p(s[0])+'.00 – '+p(s[1])+'.00'),el('em',null,n?n+' jadwal contoh':'Belum ada jadwal'));
    b.onclick=()=>{S.slot=S.slot===i?null:i;renderSlots();renderTrips();go('jadwal')};
    g.append(b);
  });
}
const CT={Surabaya:[58,26],Malang:[50,50],Jember:[66,68],Banyuwangi:[88,80]};
function renderMap(){
  const m=$('#map');
  Object.entries(CT).forEach(([c,[x,y]])=>{
    const n=TRIPS.filter(t=>t.from===c).length;
    const b=el('button','pin');b.type='button';b.style.left=x+'%';b.style.top=y+'%';
    b.setAttribute('aria-label',c+', '+n+' keberangkatan contoh');
    b.append(el('span','tg',c+' · '+n),el('span','dt'));
    b.onclick=()=>{S.slot=null;renderSlots();$('#qf').value=c;$('#qt').value='';renderTrips();go('jadwal')};
    m.append(b);
  });
}
function renderMarquee(){
  const w='JEMBER ✦ SURABAYA ✦ MALANG ✦ BANYUWANGI ✦ PILIH KURSIMU ✦ ',b=$('#mqi');
  for(let i=0;i<2;i++)b.append(el('span',null,w.repeat(3)));
}
const VW={home:'v-home',jadwal:'v-jadwal',peta:'v-peta',kursi:'seatView',pesan:'bookView',pesanan:'ordersView',detail:'orderView',tiket:'ticketView',profil:'profView'};
function show(v){
  if(!VW[v]||(v==='kursi'&&!S.trip)||(['detail','tiket'].includes(v)&&!S.cur)||(['pesan','pesanan','profil','detail','tiket'].includes(v)&&!S.user))v='home';
  if(v!=='detail')clearInterval(S.timer);
  Object.entries(VW).forEach(([k,id])=>{$('#'+id).hidden=k!==v});
  document.querySelectorAll('nav a[data-v]').forEach(a=>a.setAttribute('aria-current',a.dataset.v===(v==='kursi'?'jadwal':v)?'page':'false'));
  scrollTo({top:0});
}
function go(v){show(v);if(['home','jadwal','peta'].includes(v)&&location.hash!=='#'+v)location.hash=v}
addEventListener('hashchange',()=>{const h=location.hash.slice(1);if(['home','jadwal','peta'].includes(h))show(h)});
const REV=[['Rina','Jember → Surabaya','Kursinya bisa dipilih dari awal, jadi tidak rebutan. Berangkat tepat waktu.'],
['Dimas','Jember → Malang','Bayarnya gampang: unggah bukti, tidak lama tiketnya keluar.'],
['Salsa','Jember → Banyuwangi','Elf-nya bersih, AC dingin, dan sopirnya ramah.'],
['Bayu','Malang → Jember','Check-in tinggal tunjukkan QR. Tidak perlu antre di loket.'],
['Nadia','Surabaya → Jember','Tampilannya lucu dan tidak ribet, bahkan buat yang jarang pesan online.'],
['Fikri','Jember → Surabaya','Denah 3D-nya membantu memilih kursi dekat jendela.']];
function renderReviews(){
  const g=$('#revs');
  REV.forEach(([n,r,t])=>{const c=el('article','card rc');const st=el('div','st','★★★★★');st.setAttribute('aria-label','5 dari 5');c.append(st,el('p',null,t),el('strong',null,n),el('small','mut',' · '+r));g.append(c)});
  $('#rvBtn').onclick=()=>requireLogin(()=>toast('Ulasan hanya untuk penumpang yang perjalanannya sudah selesai.'));
}
renderAuth();renderSlots();renderMap();renderMarquee();renderReviews();renderTrips();show((location.hash||'#home').slice(1));
})();
</script>
</body>
</html>
