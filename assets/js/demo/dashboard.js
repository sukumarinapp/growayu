$(function() {
	function showTooltip(x, y, contents) {
        $('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css( {
          position: 'absolute',
          display: 'none',
          top: y + 5,
          left: x + 5
        }).appendTo("body").fadeIn(200);
     }
    
    var uploads = [[0, 9], [1, 8], [2, 5], [3, 8], [4, 5], [5, 14], [6, 10]];
    var downloads = [[0, 5], [1, 12], [2,4], [3, 3], [4, 12], [5, 11], [6, 15]];
    

});
