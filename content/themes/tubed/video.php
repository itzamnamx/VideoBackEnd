<?php include('includes/header.php'); ?>


<div class="col-md-8 col-md-offset-2 right-content-8" id="video_section" style="padding:0px;">

	<div id="video_title">
		<div class="container-fluid">
			<span class="label">You're watching:</span> <h1><?= $video->title ?></h1>
		</div>
	</div>
	<div id="video_bg" style="background-image:url(<?= Config::get('site.uploads_url') . '/images/' . str_replace(' ', '%20', $video->image) ?>)">
		<div id="video_bg_dim" <?php if($video->access == 'guest' || ($video->access == 'subscriber' && !Auth::guest()) ): ?><?php else: ?>class="darker"<?php endif; ?>></div>
		<div class="container-fluid">
			
			<?php if($video->access == 'guest' || ( ($video->access == 'subscriber' || $video->access == 'registered') && !Auth::guest() && Auth::user()->subscribed()) || (!Auth::guest() && (Auth::user()->role == 'demo' || Auth::user()->role == 'admin')) || (!Auth::guest() && $video->access == 'registered' && $settings->free_registration && Auth::user()->role == 'registered') ): ?>

				
					<?php if($video->type == 'embed'): ?>
						<div id="video_container" class="fitvid">
							<?= $video->embed_code ?>
						</div>
					<?php else: ?>
						<div id="video_container">
						<video id="video_player" class="video-js vjs-default-skin" controls preload="auto" poster="<?= Config::get('site.uploads_url') . '/images/' . $video->image ?>" data-setup="{}" width="100%" style="width:100%;">
							<source src="<?= $video->mp4_url; ?>" type='video/mp4'>
							<source src="<?= $video->webm_url; ?>" type='video/webm'>
							<source src="<?= $video->ogg_url; ?>" type='video/ogg'>
							<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
						</video>
						</div>
					<?php endif; ?>
				

			<?php else: ?>

				<div id="subscribers_only">
					<h2>Sorry, this video is only available to <?php if($video->access == 'subscriber'): ?>Subscribers<?php elseif($video->access == 'registered'): ?>Registered Users<?php endif; ?></h2>
					<div class="clear"></div>
					<form method="get" action="/signup">
						<button id="button">Signup Now<?php if($video->access == 'subscriber'): ?>to Become a Subscriber<?php elseif($video->access == 'registered'): ?>for Free!<?php endif; ?></button>
					</form>
				</div>
			
			<?php endif; ?>
		</div>
	</div>


	<div class="video-details">

		<h3>
			<?= $video->title ?>
			<span class="view-count"><i class="fa fa-eye"></i> <?php if(isset($view_increment) && $view_increment == true ): ?><?= $video->views + 1 ?><?php else: ?><?= $video->views ?><?php endif; ?> Views </span>
			<div class="favorite btn btn-default <?php if(isset($favorited->id)): ?>active<?php endif; ?>" data-authenticated="<?= !Auth::guest() ?>" data-videoid="<?= $video->id ?>"><i class="fa fa-heart"></i> <span>Favorite</span></div>
			<div class="expand btn btn-default "><i class="fa fa-expand"></i> Full Mode</div>
		</h3>



		<div class="video-details-container"><?= $video->details ?></div>

		<div class="clear"></div>
		<h2 id="tags">Tags: 
		<?php foreach($video->tags as $key => $tag): ?>

			<span><a href="/videos/tag/<?= $tag->name ?>"><?= $tag->name ?></a></span><?php if($key+1 != count($video->tags)): ?>,<?php endif; ?>

		<?php endforeach; ?>
		</h2>

		<div class="clear"></div>
		<div id="social_share">
	    	<p>Share This Video:</p>
			<?php include('partials/social-share.php'); ?>
		</div>

		<div class="clear"></div>

		<div id="comments">
			<div id="disqus_thread"></div>
		</div>
    
	</div>

</div>

<div class="col-md-2" id="right_sidebar">
<h6>Recent Videos</h6>
	<?php $videos = Video::where('active', '=', 1)->orderBy('created_at', 'DESC')->take(4)->get(); ?>
	<?php foreach($videos as $video): ?>
		<?php include('partials/video-block.php'); ?>
	<?php endforeach; ?>
</div>


		
	<script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = '<?= ThemeHelper::getThemeSetting(@$theme_settings->disqus_shortname, 'svideo') ?>';

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the comments</noscript> 

	<script src="<?= THEME_URL . '/assets/js/jquery.fitvid.js'; ?>"></script>
	<script type="text/javascript">

		$(document).ready(function(){
			$('#video_container').fitVids();
			$('.favorite').click(function(){
				if($(this).data('authenticated')){
					$.post('/favorite', { video_id : $(this).data('videoid'), _token: '<?= csrf_token(); ?>' }, function(data){});
					$(this).toggleClass('active');
				} else {
					window.location = '/signup';
				}
			});

			$('.expand').click(function(){
					if($(this).hasClass('active')){

						$('#video_section').toggleClass('col-md-12').toggleClass('col-md-offset-2').toggleClass('col-md-8');
						$('#left-sidebar').show();
						$('#right_sidebar').show();
						$(this).removeClass('active');
						$('.menu-toggle').removeClass('right');
					} else {
						if($('#left-sidebar').css('display') == 'block'){
							$('#video_section').toggleClass('col-md-8').toggleClass('col-md-offset-2').toggleClass('col-md-12');
						} else {
							$('#video_section').toggleClass('col-md-10').toggleClass('col-md-12');
						}
						$(this).addClass('active');
						$('#left-sidebar').hide();
						$('.menu-toggle').addClass('right');
						$('#right_sidebar').hide();
					}
					$(window).trigger('resize');
			});

			$('.menu-toggle').click(function(){
				
				if($(this).hasClass('right') && $(window).width() > 991){
					$('#right_sidebar').show();
					$('#video_section').removeClass('col-md-12').addClass('col-md-10');
				}  
				$(window).trigger('resize');
			});

		});

	</script>

	<!-- RESIZING FLUID VIDEO for VIDEO JS -->
	<script type="text/javascript">
	  // Once the video is ready
	  _V_("video_player").ready(function(){

	    var myPlayer = this;    // Store the video object
	    var aspectRatio = 9/16; // Make up an aspect ratio

	    function resizeVideoJS(){
	    	console.log(myPlayer.id);
	      // Get the parent element's actual width
	      var width = document.getElementById('video_container').offsetWidth;
	      // Set width to fill parent element, Set height
	      myPlayer.width(width).height( width * aspectRatio );
	    }

	    resizeVideoJS(); // Initialize the function
	    window.onresize = resizeVideoJS; // Call the function on resize
	  });
	</script>

	<script src="<?= THEME_URL . '/assets/js/rrssb.min.js'; ?>"></script>


<?php include('includes/footer.php'); ?>