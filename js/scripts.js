/*<![CDATA[*/

$(document).ready(function() {

	/* Swift smack on the wrist for IE users */
	// $.browser.msie ? $('body').prepend('<div class="crap-browser-warning">So sorry, this site just doesn\'t DO Internet Explorer. If this webpage looks sort of crappy (fingers crossed), it\'s probably because you\'re using an old and buggy browser. Actually, that IS the ONLY reason this page would look bad EVER. <br>Your browser sucks. <strong>{:/</strong><br><a href="http://www.google.com/chrome/" title="The quickest, most elegant, and least harmful browser to date.">Consider a much safer browser</a> <span>(<- get a clue, click here, <u>now</u>)</span> or <a href="http://www.microsoft.com/windows/internet-explorer">join this crappy fanclub.</a></div>') : '';

	$('#contact-form').submit(function() {
		$('.contact .loading').fadeIn();
		$(this).children().each(function(){ $(this).removeClass('error'); });
		$.ajax({
		   type: 'POST',
		   url: blog_url + '/wp-content/themes/KreativKennSplash/contact.php',
		   data: $(this).serialize(),
		   success: function(data){
				$('.contact .loading').fadeOut();
				if (data.name_error) $('#contact_name').addClass('error');
				if (data.email_error) $('#contact_email').addClass('error');
				if (data.subject_error) $('#contact_subject').addClass('error');
				if (data.message_error) $('#contact_message').addClass('error');
				if (data.sent) $('.contact .sent').fadeIn(function(){
					$(this).delay(1000).fadeOut('slow');
					$('#contact-form').children().not('#submit').each(function(){ $(this).val(''); });
				});
				else $('.contact .sent-error').fadeIn(function(){ $(this).delay(1000).fadeOut('slow'); });
			},
			error: function(){
				alert('error!');
			},
			dataType: 'json'
		});
		return false;
	});

	/* Interval Functions */
	let i = 0, spinStrands = true, loading = false, selectedStrand = 0, strandSelected = false;
	setInterval(function(){
		if (spinStrands) {
			$('.strand').each(function(){
				let strandPosition = $(this).css('backgroundPosition').split(' ')[0].replace(/[^0-9-]/g, '');
				if (strandPosition == -7650) strandPosition = 450;
				$(this).css('background-position', strandPosition - 450);
			});
		} else if (strandSelected) {
			if (selectedStrand !== 0 && selectedStrand !== 17){
				let prevStrandPos = -8550, nextStrandPos = 450;
				// preceding strands
				for (var i = selectedStrand; i >= 0; i--)
					$(`.strand._${i + 1}`).css('background-position', prevStrandPos+=450);
				// following strands
				for (var i = 0, l = 19 - selectedStrand; i < l; i++)
					$(`.strand._${1 + selectedStrand + i}`).css('background-position', nextStrandPos-=450);
			}
		}
		if (loading) {
			$('.loading').css('background-position', i);
			i -= 225;
			if (i == -4500) i = 0;
		}
	}, 100);
	$('.homepage.helix').animate({ 'height' : '120%', 'top' : '-10%' }, 4500, "easeOutCirc");
	$('.homepage.helix .selector').mouseenter(function(){
		spinStrands = false;
		selectedStrand  = Number($(this).attr('id'));
		if (selectedStrand !== 17) $(`.homepage.helix .strand._${selectedStrand + 1}`).addClass('selected');
		strandSelected = true;
	}).mouseleave(function(){
		spinStrands = true;
		$('.homepage.helix .selected.strand').css('background-position', 0).removeClass('selected');
		strandSelected = false;
	});

	let firing = false; // prevents overloading of window.scroll function
	$(window).resize(function() {
		$('#logo img').css({
			'margin-top' : $('#logo img').width() * -0.5,
			'margin-left' : $('#logo img').width() * -0.5
		});
	}).scroll(function() {
		if (!firing) {
			firing = true;
			setTimeout(function() {

				firing = false;
			}, 500);
		}
	});
});
