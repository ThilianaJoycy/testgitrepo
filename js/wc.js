jQuery(document).ready(function($){

	$loves = $('td.loves-number h3');
	$hates = $('td.hates-number h3');

	$('.product-love-hate').click(function(e){
		e.preventDefault();

		action = $(this).data('action');
		nonce = $(this).data('nonce');
		product_id = $(this).data('product-id');

		$.ajax(
			{
				url: wpajax.ajaxurl,
				method: 'POST',
				data: {
					action: action,
					nonce: nonce,
					product_id: product_id
				},
				success: function(data) {
					var res = wpAjax.parseAjaxResponse(data, 'ajax-response');

					$.each(res.responses, function(){
						if (this.id != 0) {

							$loves.text(this.supplemental.loves);
							$hates.text(this.supplemental.hates);

						}
					});
				}
			}
		);

	});
});