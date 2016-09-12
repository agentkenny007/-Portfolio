/* Declare Variables */
var i = 0, s, t, caf = 'cancelAnimationFrame', cleanHelix = false, dirtyStrand = 0, loading = false, raf = 'requestAnimationFrame', scrolling = false; selectedStrand = 0, spinStrands = true, strandSelected = false;

/* Declare Functions */
function animateHelix(){
	if (cleanHelix) $('.strand._' + dirtyStrand++).css('background-position', 0);
	if (dirtyStrand === 19){
		cleanHelix = false;
		dirtyStrand = 0;
	}
	if (spinStrands){
		$('.strand').each(function(){
			var strandPosition = $(this).css('backgroundPosition').split(' ')[0].replace(/[^0-9-]/g, '');
			if (strandPosition == -7650) strandPosition = 450;
			$(this).css('background-position', strandPosition - 450);
		});
	} else if (strandSelected){
		if (selectedStrand !== 0 && selectedStrand !== 17){
			var prevStrandPos = -8550, nextStrandPos = 450;
			// preceding strands
			for (var i = selectedStrand; i >= 0; i--)
				$(`.strand._${i + 1}`).css('background-position', prevStrandPos+=450);
			// following strands
			for (var i = 0, l = 19 - selectedStrand; i < l; i++)
				$(`.strand._${1 + selectedStrand + i}`).css('background-position', nextStrandPos-=450);
		}
	}
	requestAnimationFrame(animateHelix);
}

$(document)
	.on('mousedown', function(){ clearTimeout(t); s.stopAnimateTo(); })
	.on('mousedown', '.helix .selector', function(){
		var activeStrand = Number($(this).attr('id')) + 1;
		$('.helix .strand._' + activeStrand).addClass('active'); })
	.on('mouseenter', '.helix .selector', function(){
		cleanHelix = spinStrands = false;
		dirtyStrand = 0;
		selectedStrand  = Number($(this).attr('id'));
		if (selectedStrand !== 17) $(`.helix .strand._${selectedStrand + 1}`).addClass('selected');
		strandSelected = true; })
	.on('mouseleave', '.helix', function(){
		var strandPosition = Number($('.helix .strand._1').css('backgroundPosition').split(' ')[0].replace(/[^0-9-]/g, ''));
		for (var i = 2; i < 19; i++){
			var position = Number($('.helix .strand._' + i).css('backgroundPosition').split(' ')[0].replace(/[^0-9-]/g, ''));
			strandPosition -= 450;
			if (strandPosition === -8100) strandPosition = 0;
			if (position !== strandPosition){ cleanHelix = true; break; }
		}
		$('.helix .selected.strand').css('background-position', 0).removeClass('selected');
		strandSelected = false;
		spinStrands = true; })
	.on('mouseleave', '.helix .selector', function(){
		$('.helix .selected.strand').css('background-position', 0).removeClass('selected'); })
	.on('mouseup', '.helix .selector', function(){
	   var activeStrand = Number($(this).attr('id')) + 1;
	   $('.helix .strand._' + activeStrand).removeClass('active'); })
	.on('vmouseup', '.helix', function(e){
		spinStrands = true;
		$('.helix .selected.strand').css('background-position', 0).removeClass('selected');
		strandSelected = false; })
	.on('vmousemove', '.helix', function(e){
		cleanHelix = spinStrands = false;
		dirtyStrand = 0;
		var strand = Math.floor(e.pageY / ($(window).height() / 15)) + 3;
		selectedStrand = strand - 1;
		strandSelected = true;
		if (!$('.strand._' + strand).hasClass('selected')){
			$('.helix .selected.strand').removeClass('selected');
			$('.helix .strand._' + strand).addClass('selected');
		} })
	.ready(function(){
		requestAnimationFrame(animateHelix);
		$('.helix').animate({ 'height' : '120%', 'top' : '-10%' }, 4500, 'easeOutCirc');

		s = skrollr.init({ skrollrBody: 'wrapper' });

		/* Swift smack on the wrist for IE users */
		// $.browser.msie ? $('body').prepend('<div class='crap-browser-warning'>So sorry, this site just doesn\'t DO Internet Explorer. If this webpage looks sort of crappy (fingers crossed), it\'s probably because you\'re using an old and buggy browser. Actually, that IS the ONLY reason this page would look bad EVER. <br>Your browser sucks. <strong>{:/</strong><br><a href='http://www.google.com/chrome/' title='The quickest, most elegant, and least harmful browser to date.'>Consider a much safer browser</a> <span>(<- get a clue, click here, <u>now</u>)</span> or <a href='http://www.microsoft.com/windows/internet-explorer'>join this crappy fanclub.</a></div>') : '';

		$('#contact-form').submit(function(){
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

		var firing = false; // prevents overloading of window.scroll function
		$(window).resize(function(){
			$('#logo img').css({
				'margin-top' : $('#logo img').width() * -0.5,
				'margin-left' : $('#logo img').width() * -0.5
			});
		}).scroll(function(){
			console.log($(window).scrollTop());
			if (scrolling){ alert('scrolling'); scrolling = false; }
			if (t) clearTimeout(t);
			t = setTimeout(function(){
				if ($(window).scrollTop() < 550) s.animateTo(0, { duration: 1500, easing: 'bounce' });
				else s.animateTo(1000, { duration: 750, easing: 'outCubic' });
			}, 750);
			if (!firing){
				firing = true;
				setTimeout(function(){

					firing = false;
				}, 500);
			}
		});
	});

(function (window, raf, caf) {
	var mark = 0, suffix = raf.slice(1),
		RAF = ["r", "webkitR", "mozR", "msR", "oR"].filter(function(prefix){ return prefix + suffix in window; })[0] + suffix,
		CAF = caf in window ? true : ['ms', 'moz', 'webkit', 'o'].map(function(vendor){ return window[vendor + 'C' + caf.slice(1)] || window[vendor + 'CancelRequest' + caf.slice(6)]; })
			.filter(function(callback){ return !!callback; });

    window[raf] = window[RAF] || function(callback){
        var now = Date.now() || +new Date, callAtTime = Math.max(mark + 16, now);
        return setTimeout(function(){ callback(mark = callAtTime); }, callAtTime - now);
    };

	window[caf] = CAF === true ? window[CAF] : !!CAF ? CAF : function(id){ window.clearTimeout(id); };
}(this, raf, caf));
