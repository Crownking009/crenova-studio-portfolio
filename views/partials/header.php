<?php $pageTitle = isset($title) ? $title . ' | ' . SITE_NAME : SITE_NAME; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Crenova Studio creates brave brands, digital experiences, campaigns and content with a considered point of view.">
  <meta property="og:title" content="<?= e($pageTitle) ?>"><meta property="og:description" content="A creative studio for brands ready to be felt."><meta name="csrf-token" content="<?= e(csrf()) ?>">
  <meta name="theme-color" content="#003333"><title><?= e($pageTitle) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Mono&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>"><link rel="stylesheet" href="<?= url('assets/css/additions.css') ?>"><link rel="stylesheet" href="<?= url('assets/css/animations.css') ?>"><link rel="stylesheet" href="<?= url('assets/css/bright-theme.css') ?>">
  <script type="application/ld+json">{"@context":"https://schema.org","@type":"ProfessionalService","name":"Crenova Studio","telephone":"<?= PHONE_NUMBER ?>","url":"<?= url() ?>"}</script>
</head>
<body>
<div class="cursor-glow" aria-hidden="true"></div>
<header class="site-header"><a class="brand" href="<?= url() ?>" aria-label="Crenova Studio home"><img src="<?= url('assets/images/crenova-mark.svg') ?>" alt="Crenova Studio"><span>CRENOVA<br><em>STUDIO</em></span></a>
<button class="menu-toggle" aria-expanded="false" aria-controls="main-nav"><i></i><i></i><span>Menu</span></button>
<nav id="main-nav"><a href="<?= url('portfolio') ?>">Work</a><a href="<?= url('services') ?>">Capabilities</a><a href="<?= url('shop') ?>">Shop</a><a href="<?= url('blog') ?>">Journal</a><a href="<?= url('contact') ?>">Contact</a><a class="nav-cta" href="<?= url('book') ?>">Start a project <b>↗</b></a></nav></header>
<?php if ($message = flash('success')): ?><div class="notice success"><?= e($message) ?></div><?php endif; ?>
<?php if ($message = flash('error')): ?><div class="notice error"><?= e($message) ?></div><?php endif; ?>
<main>
