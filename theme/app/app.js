$(document).ready(function () {
	$('.show_comments').click(function(){
		$('#disqus_thread').fadeIn(300);
		$(this).hide();
	});
});