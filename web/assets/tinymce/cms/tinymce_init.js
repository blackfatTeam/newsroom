$(function() {

	//tinymce.PluginManager.load('socialFeed', window.baseUri + '/assets/tinymce/cms/socialFeed/plugin.js');
	tinymce.PluginManager.load('cms',  window.baseUri+'/assets/tinymce/cms/tinymce_cms_plugin.js');
	tinymce.init({
			selector:'textarea#tinymce_modify',
			theme: "modern",
			menubar: false,
		
			content_css: window.baseUri + "/assets/tinymce/cms/tinymce-content.css",
			
		    plugins : "paste code noneditable -cms -socialFeed advlist autolink link lists print preview pagebreak textcolor",
		   // toolbar : "bold italic underline forecolor backcolor strikethrough styleselect | alignleft aligncenter alignright alignjustify | bullist numlist link | social-feed youtube_link | cms.media | cms.facebook cms.twitter cms.instagram | code",
			toolbar : "bold italic underline forecolor backcolor strikethrough styleselect | alignleft aligncenter alignright alignjustify | bullist numlist link | social-feed youtube_link | cms.media | cms.facebook | code",
				
			//valid_styles: { '*': 'text-align,text-decoration,color' },
		    valid_elements : 'br,span[style|class],div[style|class],p[style|class],pre[style],blockquote[style],img[style|src|alt|width|height],h1,h2,h3,h4,h5,h6,strong,em,sup,sub,code,a[href|title|target],ol[class],ul[class],li[class],table[class],thead,tbody,tr,td,th',
			valid_children: 'div[iframe]',
			
			extended_valid_elements : 'iframe[title|width|height|src|frameborder|allowfullscreen],'+
									  'media[id|class|object|itemno|width|height|src|controls|key],'+
									  'facebook[class|data-href|data-width],' +
									  'instagram[class|data-html],' +
									  'div[class],' +
									  'i[class]',
			
			custom_elements : 'media,youtube',
			paste_as_text: true,
			/////////////////////////////////////full path url image
			relative_urls : false,
			remove_script_host : false,
			convert_urls : true,
			////////////////////////////////////
			//inline_styles : true,
	
			init_instance_callback: function(){
	            ac = tinyMCE.activeEditor;
	        }
			
		   
	});

	$('#table-social-listdata').on('click', 'td .social-select', function() {
		var imgSrc = 'http://placehold.it/100x60';
		var id = $(this).attr('data-id');
		var rowId = 'row-'+$(this).attr('data-channelid')+'-'+$(this).attr('data-id');
		var content = $('#'+rowId).text();
		var postBy = $(this).attr('data-postBy');
		var iconPath = null;

		switch ($(this).attr('data-object-type')) {
			case 'facebook':
				iconPath = "/assets/img/icons/facebook-icon.png";
				break;
			case 'g+':
				iconPath = "/assets/img/icons/google-plus-icon.jpg";
				break;
			case 'instagram':
				iconPath = "/assets/img/icons/Instagram-icon.png";
				break;
			case 'twitter':
				iconPath = "/assets/img/icons/twitter-icon.png";
				break;
		}

		
		var html = '<entity type="social" data-id="'+id+'">' +
			'<span class="caption"> user : '+postBy+'</span> <span class="caption"> เนื้อหา : '+content+'</span>' +
			'<img src="'+iconPath+'" alt="" width="36"><br/>'
		'</entity>';
		
		insertContent(html);
	});

	$('#btn-search-social').click(function() {
		var inputCb = $('input[name^=socialType]');
		var arrScocialType = new Array();
		$( inputCb ).each(function(ob) {
			if($( this ).is(':checked')){
				arrScocialType.push($( this ).val());
			}
		});
		
		var jsonType = JSON.stringify(arrScocialType);

		selector = $('#table-social-listdata');
		$('tbody tr:not(:first)', selector).remove();
		$.get(window.baseUri + 'mediaObject/socialList', {
			keyword: $('#search-social').val(),
			scocialType: jsonType,
		}).done(function(result) {
			$.each(result, function(i) {
				var tb = $('.table tbody', selector);
				tb.append(newSocialItem(tb, result[i]));
			});
			if (result.length < 1) {
				var tb = $('.table tbody', selector);
				tb.append(newSocialItem(tb, {id: 0, title: '[ยังไม่มีรายการ]', url: 0}));
			}
		});
	});

	function newSocialItem(tb, data) {
		var newRow = $(tb.find('tr:first')).clone().html();
		var rowId = 'row-'+data.channelId+'-'+data.feedId;
		
		newRow = newRow.replace('{id}', data.feedId);
		newRow = newRow.replace('{rowId}', rowId);
		newRow = newRow.replace('{content}', data.content);
		newRow = newRow.replace('{id}', data.feedId);
		newRow = newRow.replace('{channelId}', data.channelId);
		newRow = newRow.replace('{type}', data.social);
		newRow = newRow.replace('{postBy}', data.postBy);
		return '<tr data-object="content" data-id="' + data.feedId + '"' + '>' + newRow + '</tr>';
	}
});

function insertContent(html) {
	tinyMCE.activeEditor.insertContent(html);
	var node = tinyMCE.activeEditor.selection.getNode();
	while(node.parentNode.tagName != 'BODY')
		node = node.parentNode;
	tinyMCE.activeEditor.selection.select(node);
	tinyMCE.activeEditor.selection.collapse();
}