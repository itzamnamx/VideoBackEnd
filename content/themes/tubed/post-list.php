<?php include('includes/header.php'); ?>

<?php if(isset($page_title)): ?>
	<h3 class="col-md-10 col-md-offset-2 right-content-10 header"><?= $page_title ?><?php if(isset($page_description)): ?><span><?= $page_description ?></span><?php endif; ?></h3>
<?php endif; ?>

<div class="col-md-10 col-md-offset-2 right-content-10">

		<div id="media-grid">
			
			<div class="row">

				<?php include('partials/post-loop.php'); ?>

			</div>
		</div>

		<?php include('partials/pagination.php'); ?>

</div>


<?php include('includes/footer.php'); ?>