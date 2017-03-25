//検索バーの検索候補
$(function() {
	var termTemplate = "<span class='ui-autocomplete-term'>%s</span>";
	$('#search-bar').autocomplete({
		source: "/templates/autocomplete.php",
		//一致する部分を太字に
		open: function (e, ui) {
			var acData = $(this).data('ui-autocomplete');
			acData
			.menu
			.element
			.find('li')
			.each(function () {
				var me = $(this);
				var keywords = acData.term.split(' ').join('|');
				me.html(me.text().replace(new RegExp("(" + keywords + ")", "gi"), '<b>$1</b>'));
			});
			//iPhoneでダブルタップしないといけない問題を解消
			 $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
		}
	});
});
