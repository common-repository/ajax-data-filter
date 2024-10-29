jQuery(document).ready(function($){
	
   $('#data_form').submit(function (e) {
       event.preventDefault();
	   
		var yf_form_data = $('#data_form').serialize();
		var action ="yf_data_filter";
		var yf_nonce = yf_data.y_filter_nonce;
		var loading_img = $('.image-loading').val();
		var results_div = $('.results-div').val();


       if(yf_form_data == ''){
	   alert('select a filter!');
	   return false;
	   }else {	
	   $(results_div).html("<img style='margin:auto;float:none;display:block;width:80px;' src='"+loading_img+"'>");
	   }
	   
       $.ajax({
       url:yf_data.d_ajax_url,
       type:'post',
       dataType:'json',
	   data: yf_form_data + '&action=' + action + '&yf_nonce=' + yf_nonce,
       success:function(response){

                if(parseInt(response.end) > 0) {
				
                  $(results_div).html(response.content);
				  
				  		if(response.page_num > 0){
							$(results_div).append(' <a href="#" data-page="2" class="loadmore">load more</a> ');
		
							}
				  
				  }


       },
       error:function(){}

       });
	   

   });

   
    $(document).on('click','.loadmore',function(event){
        event.preventDefault();
		
	    var yf_form_data = $('#data_form').serialize();

        var $this = $(this);
        var $page = parseInt($this.data('page'));
		var yf_nonce = yf_data.y_filter_nonce;
		var action ="yf_data_filter";
		var results_div = $('.results-div').val();
		var loading_img = $('.image-loading').val();
		
		$(results_div).html("<img style='margin:auto;float:none;display:block;width:80px;' src='"+loading_img+"'>");  
		
	   $.ajax({
       url:yf_data.d_ajax_url,
       type:'post',
       dataType:'json',
	   data: yf_form_data + '&page=' + $page + '&action=' + action + '&yf_nonce=' +yf_nonce,
       success:function(response){
	   
	   if(parseInt(response.end) > 0) {
	   
	    $(results_div).html(response.content);	
		$this.data('page',parseInt($page+1));
				  		if(response.page_num > 0){		
							$(results_div).append(' <a href="#" data-page="'+($page+1)+'" class="loadmore">load more</a> ');
							}
		
		} else{

		 $(results_div).html('<h4 style="text-align:center"> No More Post! </h4>');
		}
	    },
	   error:function(){}
		
		
	});	
}); 

});