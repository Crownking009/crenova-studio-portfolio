<?php require __DIR__ . '/partials/header.php'; $projects = Project::featured(); $services = Service::all(); $products = Product::all(); ?>
<section class="hero"><div class="hero-grid"></div><div class="hero-copy reveal"><span class="eyebrow">Independent creative studio · Lagos / Everywhere</span><h1>We give <i>bold ideas</i><br>their own gravity.</h1><p>Strategy, identity, digital and content for people building things with a pulse.</p><div class="hero-actions"><a class="button primary" href="<?= url('portfolio') ?>">See our work <b>↗</b></a><a class="button text" href="<?= url('book') ?>">Book a consultation <b>↗</b></a></div></div><div class="hero-art reveal delay"><div class="orb orb-one"></div><div class="orb orb-two"></div><div class="hero-card glass"><span>Currently shaping</span><strong>the next thing<br>you can’t ignore.</strong><small>Scroll to explore ↓</small></div><div class="stamp">CREATIVE<br>MATTERS<br>◎</div></div></section>
<section class="section">
	<div class="cost-calculator panel reveal delay">
		<h3>Project cost estimator</h3>
		<div class="presets">
			<button type="button" data-preset="basic">Basic</button>
			<button type="button" data-preset="standard">Standard</button>
			<button type="button" data-preset="premium">Premium</button>
		</div>
		<form>
			<div class="form-row">
				<label><input type="checkbox" name="branding"> Branding</label>
				<label><input type="checkbox" name="website"> Website</label>
				<label><input type="checkbox" name="app"> Mobile app</label>
				<label><input type="checkbox" name="campaign"> Campaign</label>
			</div>
			<div class="form-row">
				<label>Complexity: <select name="complexity"><option value="small">Small</option><option value="standard" selected>Standard</option><option value="large">Large</option></select></label>
				<label><input type="checkbox" name="images"> Additional images</label>
				<label><input type="checkbox" name="cms"> CMS / Editor</label>
			</div>
			<div class="form-row">
				<label>Adjustment: <input type="range" name="adjust" min="70" max="160" value="100"> <span class="adjust-value">100%</span></label>
			</div>
			<div class="estimate">
				<div>
					<div class="estimate-amount">₦0</div>
					<ul class="estimate-details"></ul>
				</div>
				<div class="estimate-actions">
					<button type="button" class="save-estimate">Save</button>
					<button type="button" class="contact-estimate">Contact</button>
				</div>
			</div>
		</form>
	</div>
</section>
<section class="intro section"><div class="section-number">01 / Our point of view</div><div class="intro-layout"><h2 class="display">We’re not here to make things <i>pretty.</i><br>We’re here to make them <i>matter.</i></h2><div><p class="large-copy">Crenova is a creative partner for ambitious brands. We pair clarity with craft, instinct with intention, and make work people feel before they understand it.</p><a class="inline-link" href="<?= url('services') ?>">How we help <b>→</b></a></div></div></section>
<section class="work-section section dark"><div class="section-head"><div><span class="eyebrow lime">Selected work</span><h2>Made with <i>conviction.</i></h2></div><a class="button outline-light" href="<?= url('portfolio') ?>">All projects <b>↗</b></a></div><div class="work-carousel carousel" data-carousel><?= implode('', array_map(function($p){ return '<article class="work-card slide"><a href="'.url('project/'.$p['slug']).'"><img loading="lazy" src="'.e($p['image']).'" alt="'.e($p['title']).'"><div class="work-overlay"><span>'.e($p['category']).'</span><h3>'.e($p['title']).'</h3><b>↗</b></div></a></article>'; }, $projects)) ?></div><div class="carousel-controls"><button data-prev aria-label="Previous project">←</button><span class="dots"></span><button data-next aria-label="Next project">→</button></div></section>
<section class="services-tease section"><div class="section-head"><div><span class="eyebrow">What we do</span><h2>Big thinking,<br><i>beautifully made.</i></h2></div><p>From the first question to the final launch, we bring the right people and energy to every stage.</p></div><div class="service-rail carousel" data-carousel><?= implode('', array_map(fn($s) => '<article class="service-card slide"><span class="service-icon">'.e($s['icon'] ?? '✦').'</span><h3>'.e($s['title']).'</h3><p>'.e($s['description']).'</p><a href="'.url('services').'" aria-label="Explore '.e($s['title']).'">↗</a></article>', $services)) ?></div></section>
<section class="statement"><div class="statement-art"><div class="ripple"></div><span>CRENOVA<br>STUDIO</span></div><div class="statement-copy"><span class="eyebrow lime">Why Crenova</span><h2>We make a business look like it knows <i>exactly who it is.</i></h2><div class="reasons"><div><b>01</b><strong>Clear by design</strong><p>Every choice earns its place.</p></div><div><b>02</b><strong>Brave together</strong><p>Warm collaboration, candid thinking.</p></div><div><b>03</b><strong>Built to move</strong><p>Systems made for what’s next.</p></div></div></div></section>
<section class="products section"><div class="section-head"><div><span class="eyebrow">For growing brands</span><h2>Useful things for<br><i>right now.</i></h2></div><a class="button outline" href="<?= url('shop') ?>">Visit the shop <b>↗</b></a></div><div class="product-grid"><?= implode('', array_map(fn($p) => '<article class="product-card"><div class="product-image"><img loading="lazy" src="'.e($p['image']).'" alt="'.e($p['name']).'"><button class="add-cart" data-product="'.e(json_encode($p, JSON_HEX_APOS|JSON_HEX_QUOT)).'">Add +</button></div><p>'.e($p['category']).'</p><h3>'.e($p['name']).'</h3><strong>₦'.number_format((int)$p['price']).'</strong></article>', $products)) ?></div></section>
<section class="testimonials section dark"><span class="eyebrow lime">In good company</span><div class="testimonial-carousel carousel" data-carousel><blockquote class="slide">“Crenova gave us a way to talk about ourselves that finally felt true. The whole process was generous, sharp and genuinely exciting.”<footer><b>Yewande A.</b><span>Founder, Amina Atelier</span></footer></blockquote><blockquote class="slide">“They found the heart of our idea, then made it impossible to miss.”<footer><b>Chima O.</b><span>Director, Oro Coffee</span></footer></blockquote><blockquote class="slide">“The final site looks exceptional, but more importantly, it works exceptionally hard for us.”<footer><b>Maryam T.</b><span>F&F Studio</span></footer></blockquote></div><div class="client-ticker"><span>AMINA ATELIER</span><span>ORO COFFEE</span><span>F&F STUDIO</span><span>THE SUNDAY EDIT</span><span>AMINA ATELIER</span><span>ORO COFFEE</span></div></section>
<section class="journal section"><div class="section-head"><div><span class="eyebrow">From the journal</span><h2>Things we’re<br><i>thinking about.</i></h2></div><a class="inline-link" href="<?= url('blog') ?>">More notes <b>→</b></a></div><div class="journal-grid"><?php foreach (Blog::all() as $post): ?><article><span><?= e($post['category']) ?> · 4 min read</span><h3><a href="<?= url('article/'.$post['slug']) ?>"><?= e($post['title']) ?></a></h3><p><?= e($post['excerpt']) ?></p><a href="<?= url('article/'.$post['slug']) ?>">Read note ↗</a></article><?php endforeach; ?></div></section>
<?php require __DIR__ . '/partials/footer.php'; ?>
