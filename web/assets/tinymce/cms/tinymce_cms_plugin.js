tinymce.create('tinymce.plugins.cmsPlugin',{
	cmsPlugin : function(editor,url) {
		editor.addButton('cms.gallery', {
			title : 'อัลบั้ม',
			icon: 'icon gallery-icon',
			onclick: function() {
				var selector = $('#embed-popup-items');
				
                $('tbody tr:not(:first)', selector).remove();
                selector.attr('data-object', 'gallery').modal('toggle');
			}
	      });
		
		editor.addButton('cms.media', {
			title : 'รูปภาพ',
			icon: 'image',
			onclick: function() {
		
				selector = $('#embeded-media-items');
				$('tbody tr:not(:first)', selector).remove();
				$.post(baseUri+'/media', {
					id: $('#content-main').attr('data-id'),
					type: $('#content-main').attr('data-type')
				}).done(function(result) {
					if(typeof result == 'string'){
						result = JSON.parse(result);
					}
					$.each(result, function(i) {
					
						var tb = $('.table tbody', selector);
						tb.append(newMediaItem(tb, result[i]));
					});
				});				
                selector.modal('toggle');
                selector.removeClass('hide');
			}
	      });
		
		
		/* icon social */
		editor.addButton('cms.facebook', {
			title : 'Facebook',
			icon: 'icon facebook-icon',
			class: 'mce_image',
			onclick: function() {
				
				// Open window with a specific url
				editor.windowManager.open({
					title: 'ใส่ลิงค์ฝังโพสต์ จาก Facebook',
					body: [
						{
							minWidth: editor.getParam("code_dialog_width", 600),
							minHeight: editor.getParam("code_dialog_height", 
							Math.min(tinymce.DOM.getViewPort().h - 100, 100)),
							type: 'textbox', 
							name: 'facebookEmbeded', 
							label: 'URL จากหน้า Facebook',
							
						}
					],
					onsubmit: function(e) {
	
						// Insert content when the window form is submitted
						var facebookUrl = e.data.facebookEmbeded;
						
						var html = '<facebook class="fb-post facebook-post" data-href="'+facebookUrl+'" data-width="500">"'+facebookUrl+'"</facebook>';		
					
						//editor.execCommand('insertHTML', false,html);
						editor.insertContent(html);				
					}
				});
			}
		
	      });
		
		editor.addButton('cms.twitter', {
			title : 'Twitter',
			icon: 'icon twitter-icon',
			class: 'mce_image',
			onclick: function() {
				// Open window with a specific url
				editor.windowManager.open({
					title: 'ใส่ลิงค์ จาก Twitter',
					body: [
						{
							minWidth: editor.getParam("code_dialog_width", 600),
							minHeight: editor.getParam("code_dialog_height", 
							Math.min(tinymce.DOM.getViewPort().h - 100, 100)),
							type: 'textbox', 
							name: 'twitterEmbeded', 
							label: 'URL จากหน้า Twitter'
						}
					],
					onsubmit: function(e) {
						
						var twitterUrl = e.data.twitterEmbeded;
						var objectType = 'twitter';
						
						$.get(baseUri+'/contents/twitterapi', {
							objectName: twitterUrl,
						}).done(function(result) {
							if(typeof result == "string"){
								result = JSON.parse(result);
							}
							debugger;
						});
						
						
						if($(twitterUrl.anchor()).length > 1){
							var lengthObj = $($(twitterUrl)[0]).find('a').length-1;
							var newUrl = $($($(twitterUrl)[0]).find('a')[lengthObj]).attr('href');
							var titleName = $($(twitterUrl)[0]).find('p').text().replace(/\http.*/g, '');
							var twitterName = twitterUrl.replace(/.*\@/g, '').replace(/[)].*/g, '');
							var postsId = newUrl.replace(/.*[/]/g, '');
						}else{
							twitterName = twitterUrl.replace(/\http.*[/]/g, '');
							titleName = twitterUrl;
						}
					
						if(twitterUrl){
							var html = '<p></p><entity type="' + objectType + '" data-id="'+ postsId +'" data-name="'+ twitterName +'" data-title="'+ titleName +'">' +
					        			//'<img src="'+facebookUrl+'" alt="">' +
										'<p class="">' + objectType + '</p>' +
					        			'<p class="caption">'+ twitterName + ': '+ titleName + '</p>' +
					        		'</entity><p></p>';
							
							var html = '<twitter data-href="'+twitterUrl+'" >"'+twitterUrl+'"</twitter>';		
					
							editor.insertContent(html);
						}else{
							tinyMCE.activeEditor.windowManager.alert('กรุณาใส่ URL ของ youtube.');
						}
					}
				});
			}
		
	      });
		
		editor.addButton('cms.instagram', {
			title : 'Instagram',
			icon: 'icon instagram-icon',
			class: 'mce_image',
			onclick: function() {
				// Open window with a specific url
				editor.windowManager.open({
					title: 'ใส่ลิงค์ จาก Instagram',
					body: [
						{
							minWidth: editor.getParam("code_dialog_width", 600),
							minHeight: editor.getParam("code_dialog_height", 
							Math.min(tinymce.DOM.getViewPort().h - 100, 100)),
							type: 'textbox', 
							name: 'instagramSource', 
							label: 'URL จากหน้า Instagram'
						}
					],
					onsubmit: function(e) {
						
						// Insert content when the window form is submitted
						window.instagramUrl = e.data.instagramSource;	
						window.objectType = 'instagram';
						
						if($(instagramUrl.anchor()).length > 1){
							window.instagramNewUrl = $($(instagramUrl)[0]).find('a').attr('href');
						}else{
							window.instagramNewUrl = window.instagramUrl
						}

						$.get(baseUri+'/contents/instagramapi', {
							objectName: window.instagramNewUrl,
						}).done(function(result) {
									//debugger;		
							var instagramNewUrl = window.instagramNewUrl;
							var objectType = window.objectType;
							var postsId = instagramNewUrl.substring(0, instagramNewUrl.length-1).replace(/\http.*[/]/g, '');
							debugger;
							var thumbnail_url = result.thumbnail_url;
							/*var html = '<p></p><entity type="' + objectType + '" data-id="'+ postsId +'" data-name="'+ result.author_name +'">' +
					        			//'<img src="'+facebookUrl+'" alt="">' +
										'<p class="">' + objectType +' : '+ result.author_name + '</p>' +
					        			'<p class="caption">' + result.title + ': '+ result.author_name + '</p>' +
					        		'</entity><p></p>';
							editor.insertContent(html);*/
							var html = '<instagram class="facebook-post" data-html="'+result.html+'">'+result.title+'</instagram>';	
							//var html = result.html;		

							editor.insertContent(html);
						}).fail(function(result) {
							var instagramNewUrl = window.instagramNewUrl;
							var objectType = window.objectType;
							var instagramName = instagramNewUrl.substring(0, instagramNewUrl.length-1).replace(/\http.*[/]/g, '');
							
							var html = '<p></p><entity type="' + objectType + '" data-name="'+ instagramName +'">' +
						        			//'<img src="'+facebookUrl+'" alt="">' +
											'<p class="">' + objectType + '</p>' +
						        			'<p class="caption">' + objectType + ': '+ instagramName + '</p>' +
						        		'</entity><p></p>';
							editor.insertContent(html);
						  })
						  .always(function() {
						    //alert( "finished" );
						  });

					}
				});
			}
		
	      });
		
		/*---end---*/
		editor.addButton('cms.video', {
			title : 'วิดีโอ',
			icon: 'media',
			onclick: function() {
				var selector = $('#embed-popup-items');
				
                $('tbody tr:not(:first)', selector).remove();
                selector.attr('data-object', 'video').modal('toggle');
			}
	      });
		
		editor.addButton('youtube_link', {
			title : 'Youtube',
			icon: 'embed youtube-embed',
			class: 'mce_image',
			onclick: function() {

				// Open window with a specific url
				editor.windowManager.open({
					title: 'ใส่วิดีโอ จาก Youtube',
					body: [
						{
							minWidth: editor.getParam("code_dialog_width", 600),
							minHeight: editor.getParam("code_dialog_height", 
							Math.min(tinymce.DOM.getViewPort().h - 200, 200)),
							type: 'textbox', 
							name: 'youtubeSource', 
							label: 'URL จากหน้า youtube'
						}
					],
					onsubmit: function(e) {
						// Insert content when the window form is submitted
						var youtubeUrl = e.data.youtubeSource;
						if(youtubeUrl){
			        		var match = /\?v\=([\w-]+)/.exec(youtubeUrl);
			        		//var html = '<youtube key="' + match[1] + '"><iframe width="560" height="315" src="//www.youtube.com/embed/'+ match[1]+'" frameborder="0"></iframe></youtube>';
							
							var html = '&nbsp;<div class="video-container"><iframe width="200" height="130" src="//www.youtube.com/embed/'+ match[1]+'" frameborder="0"></iframe></div>&nbsp;';

							editor.insertContent(html);
						}else{
							tinyMCE.activeEditor.windowManager.alert('กรุณาใส่ URL ของ youtube.');
						}
					}
				});
			}
		
	      });
	}
});
tinymce.PluginManager.add('cms', tinymce.plugins. cmsPlugin);

function newMediaItem(tb, data) {

	var newRow = $(tb.find('tr:first')).clone().html();
	newRow = newRow.replace(/{id}/g, data.id);
	newRow = newRow.replace(/{thumbnail}/g, data.thumbPath);
	newRow = newRow.replace(/{title}/g, data.title);
	newRow = newRow.replace(/{fullPath}/g, data.fullPath);
	return '<tr data-id="' + data.id + '"' + '>' + newRow + '</tr>';
}

function newListItem(tb, data) {
	var newRow = $(tb.find('tr:first')).clone().html();
	
	newRow = newRow.replace(/{thumbnail}/g, data.preview);
	newRow = newRow.replace(/{title}/g, data.title);
	newRow = newRow.replace(/{id}/g, data.id);
	newRow = newRow.replace(/{itemNo}/g, data.itemNo);
	
	return '<tr data-object="' + data.objectType + '" data-id="' + data.id + '">' + newRow + '</tr>';
}

// popup entity embeder
$('#embeded-media-items').on('click', 'td .media-select', function() {
	var container = $(this).closest('tr');
	var id = container.attr('data-id');
	var imgSrc = thumbnailTmp;
	var imgId = '';
	
	if($(this).attr('data-source')){
		imgSrc = $(this).attr('data-source');
		imgId = $(this).attr('data-refid');
	}
	
	var html = '<img class="img-responsive" data-imgId="xxy'+imgId+'yxx" src="'+imgSrc+'">';

	insertContent(html);
});

// Search button event
$('#embed-popup-items #btn-search').click(function() {
	selector = $('#embed-popup-items');
	var objectType = selector.attr('data-object');
	var params = {
		q: $('#search-key').val(),
	};
	
	// special condition for each objectType
	switch(objectType) {
		case 'video':
			params.type = 1;	// TYPE_NEWSCLIP
		break;
	}
	
	$('tbody tr:not(:first)', selector).remove();
	
	$.get(App.baseUri + objectType, params).done(function(result) {		
		if (result.length < 1) {
			var tb = $('.table tbody', selector);
			tb.append(newListItem(tb, {id: 0, title: '[ยังไม่มีรายการ]', url: 0}));
		}
		else {
			$.each(result, function(i) {
				var tb = $('.table tbody', selector);
				tb.append(newListItem(tb, result[i]));
			});
		}
	});
});

objectMap = {
	gallery: 'อัลบั้ม',
	person: 'บุคคล',
	video: 'วิดีโอ',
	facebook: 'Facebook'
};

// insert button clicked
$('#embed-popup-items').on('click', 'a', function() {
	var container = $(this).closest('tr');
	var id = container.attr('data-id');
	var objectType = container.attr('data-object');	
	var title = $('td:eq(2)', container).text();
	
	var imgSrc = $('td:eq(1) img', container).attr('src');
	if (imgSrc == undefined)
		imgSrc = 'http://placehold.it/100x60';

	var html = '<entity type="' + objectType + '" data-id="'+id+'">' +
		'<img src="'+imgSrc+'" alt="">' +
		'<p class="caption">' + objectMap[objectType] + ': '+ title + '</p>' +
	'</entity>';	
	insertContent(html);
});