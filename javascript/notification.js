(function($) {
	$.entwine('ss', function($){
		
		$("#page-title-heading").entwine({
			onmatch: function(e) {
				var _this = this;
				$.getJSON( $('base').attr('href') + "membernotifications/notifications", function( data ) {
					var items = '';
					$.each( data, function( key, obj ) {
						items += '<div class="section-comments-comment"> <p>'+obj.message+'</p> \
							<p>'+obj.user+' <span>Listed '+obj.date+'</span></p> \
							</div>';
					});
					_this.prepend('\
						<span class="section-icon icon icon-16 icon-comments" \
						id="section-icon-comments"></span> \
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
			}
		});
	});
}(jQuery));