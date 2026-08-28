<?php require __DIR__ . '/partials/header.php'; $projects=Project::all(); ?>
<section class="page-hero"><span class="eyebrow">Selected work / 2019—Now</span><h1>Proof that <i>feeling</i><br>is a strategy.</h1><p>Brand worlds, digital products and campaigns built to leave their mark.</p></section>
<section class="section portfolio-list"><div class="filters"><button class="active" data-filter="all">ALL WORK</button><button data-filter="WEBSITE">WEBSITE</button><button data-filter="MOBILE APP">MOBILE APP</button><button data-filter="SOFTWARE">SOFTWARE</button><button data-filter="GRAPHICS DESIGN">GRAPHICS DESIGN</button><button data-filter="PRODUCTS DESIGN">PRODUCTS DESIGN</button><button data-filter="PRINTING">PRINTING</button><button data-filter="BRANDING">BRANDING</button><button data-filter="OTHERS">OTHERS</button></div><div class="portfolio-grid"><?php foreach($projects as $index=>$p): ?><article class="portfolio-item" data-category="<?= e($p['category']) ?>"><a href="<?= url('project/'.$p['slug']) ?>"><div class="portfolio-image"><img loading="lazy" src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>"><span><?= e($p['category']) ?></span></div><h2><?= e($p['title']) ?><b>↗</b></h2><p><?= e($p['description']) ?></p></a></article><?php endforeach; ?></div></section>
<section class="section">
	<div class="cost-calculator panel reveal">
		<h3>Estimate a project</h3>
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
<?php require __DIR__ . '/partials/footer.php'; ?>
