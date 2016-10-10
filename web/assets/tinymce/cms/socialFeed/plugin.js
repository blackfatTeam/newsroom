/**
 * plugin.js
 */

/*global tinymce:true */

tinymce.create('tinymce.plugins.SocialFeedPlugin',{
	SocialFeedPlugin : function(editor,url){
		editor.addButton('social-feed', {
			title : 'Social Feed Content',
			icon: 'anchor',

			onclick: function() {
				$('#social-media').removeClass('hide');
                $('#social-media').modal('toggle');
			}
		
	      });
	}
});

tinymce.PluginManager.add('socialFeed', tinymce.plugins. SocialFeedPlugin);
