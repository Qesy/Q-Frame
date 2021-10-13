//drag
/**
 * @auth:sole
 * @time:2015-8-17
 * @email:macore@163.com
 */
/*
 * options: json
 * {
 * ┌─────────────┬───────────┬───────────────────┬───────────────────────────────────────────────────┐
 * │  optionName │ type      │ value             │ descrip                                           │
 * ├─────────────┼───────────┼───────────────────┼───────────────────────────────────────────────────┤
 * │  dir        │ string    │ xy[default],x,y   │ 方向                                               │
 * ├─────────────┼───────────┼───────────────────┼───────────────────────────────────────────────────┤
 * │  limit      │ int       │ 0[default]        │ 距离窗口边距                                        │ 
 * ├─────────────┼───────────┼───────────────────┼───────────────────────────────────────────────────┤
 * │  target     │ obdject   │ jQuery对象         │ 需要移动的层的jquery对象      	                      │
 * ├─────────────┼───────────┼───────────────────┼───────────────────────────────────────────────────┤
 * │  move       │ function  │ funciton(){},     │ 鼠标移动的回调函数，返回当前的位置:{x:120, y:20}        │
 * └─────────────┴───────────┴───────────────────┴───────────────────────────────────────────────────┘
*/
;(function($){
    $.fn.drag = function(o){
		var def = {
			target : '',
			dir : 'xy',
			limit:0
		};
		var opt = $.fn.extend({}, def, o);
		return this.each(function(){
			var _this = $(this);

			var oTarget = opt.target || _this.parent(),
				dir = opt.dir,
				limit = opt.limit;

			_this.live('mousedown', function(e){
				var defPos = _this.offset();
				var x = e.clientX - defPos.left, y = e.clientY - defPos.top + $(window).scrollTop();

				zIndex = (new Date().getTime()+'').substring(7);
				oTarget.css('z-index', zIndex);
				$(document).bind('mousemove', function(e){
					var l = e.clientX - x, t = e.clientY - y;
					if(l<limit){ l = limit; }
					if(t<limit){ t = limit; }
					if(l>$(window).width()-oTarget.width()-limit){
						l=$(window).width()-oTarget.width()-limit;
					}
					if(t>$(window).height() - oTarget.outerHeight()-limit){
						t=$(window).height() - oTarget.outerHeight()-limit;
					}
					if( dir.indexOf('x') >= 0 ){ oTarget.css('left',l); }
					if( dir.indexOf('y') >= 0 ){ oTarget.css('top',t); }
					opt.move && opt.move({x:l, y:t});
					return false;
				});
				$(document).bind('mouseup', function(e){
					$(document).unbind('mousemove');
					$(document).unbind('mouseup');
					oTarget.releaseCapture && oTarget.releaseCapture();
					return false;
				});
				oTarget.setCupture && oTarget.setCapture();
				e.stopPropagation();
				return false;
			});
		});
	};
})(jQuery);