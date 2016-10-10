var UIPopupTwitterModals = function () {

    return {
        //main function to initiate the module
        init: function () {
        
            // general settings
            $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner = 
              '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
                '<div class="progress progress-striped active">' +
                  '<div class="progress-bar" style="width: 100%;"></div>' +
                '</div>' +
              '</div>';

            $.fn.modalmanager.defaults.resize = true;


            //ajax demo:
            var $modal = $('#twitter-modal-popup');
            
            $('ul.pager').delegate('#twitter-modal', 'click', function() {
            	// create the backdrop and wait for next modal to be triggered
                $('body').modalmanager('loading');

                var contentType = $('#Content_type').val();
                var cat_id = $(this).attr('data-cat-id');
                var cat_title =   $(this).attr('data-cat-title');
                setTimeout(function(){
                    $modal.load(App.baseUri+'twitterFeed/popup?&type=categoryTree&elId='+cat_id+'&elText='+cat_title+'&contentType='+contentType+'&random='+Math.random(), '', function(){
                    $modal.modal();
                  });
                }, 1000);
            });

        }

    };

}();