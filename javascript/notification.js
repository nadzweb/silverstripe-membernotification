(function($) {
	$.entwine('ss', function($){
		
		$("#page-title-heading").entwine({
			onmatch: function(e) {
				var _this = this;
				$.getJSON( $('base').attr('href') + "membernotifications/notifications", function( data ) {
					var items = '', unread = 0;
					if(data.length > 0) {
						$.each( data, function( key, obj ) {
							items += '<div class="section-comments-comment" data-commentid="'+obj.id+'" data-readstatus="'+(obj.read=='0' ? false : true)+'"> <p>'+obj.message+'</p> \
								<p>'+obj.user+' <span>Listed '+obj.date+'</span></p> \
								</div>';
							if(obj.read == '0') unread += 1;
						});
					} else items = '<p>There are no notifications, please check later!</p>';
					
					var _countHTML = (unread == 0) ? '' : '<div class="icon-comment-count"><span>'+unread+'</span></div>';
					_this.prepend(_countHTML + ' \
						<div class="section-icon icon icon-16 icon-comments" \
						id="section-icon-comments"></div> \
						<div class="section-comments-wrap hide" > \
							<div class="section-comments-content" >' + items +	' </div> \
						</div> \
				   ');
				});
				
				this._super();
			}
		});
		
		$("#section-icon-comments").entwine({
			onclick: function(e) {
				var _commentDiv = $('div.section-comments-wrap');
				var _currentClass = (_commentDiv.hasClass('hide') ? 'hide' : 'show');
				var _newClass = (_currentClass=='hide' ? 'show' : 'hide');
				
				_commentDiv.addClass(_newClass);
				_commentDiv.removeClass(_currentClass);
				
				doUpdate(_newClass);
				
			}
		});
		
		function doUpdate(clazz){
			if(clazz == 'show'){
				var commentIDs = [], 
					allStatusRead = true,
					commentDiv = $('div.section-comments-wrap .section-comments-content .section-comments-comment');
				$.each( commentDiv, function( key, obj ) {
					if($(this).attr('data-readstatus') == 'false'){
						commentIDs.push($(this).attr('data-commentid'));
						allStatusRead = false;
					}
				});
				if(!allStatusRead){
					$.post( $('base').attr('href') + "membernotifications/notifications", { commentIds: commentIDs.join() })
						.done(function( data ) {
							// http 200 OK
							$('div.section-comments-wrap').parent().find('div.icon-comment-count').remove();
							$.each( commentDiv, function( key, obj ) {
								$(this).attr('data-readstatus', 'true');
							});
						})
						.fail(function () {
						
						});
				}
				
			}
		}
	});
}(jQuery));