
window.addEventListener('DOMContentLoaded', function(){

	var image = document.querySelector('[name=image]');

	image.addEventListener('change', function(e){

		if( e.target.files[0].type === 'image/jpeg' || e.target.files[0].type === 'image/png' ) {

			// アップロードしたファイルのURLを取得
			var upload_file_url = URL.createObjectURL(e.target.files[0]);

			// img要素を作成
			var img_element = document.createElement("img");
			img_element.src = upload_file_url;
			img_element.alt = e.target.files[0].name;
			img_element.width = 100;
			img_element.onload = function(){
				URL.revokeObjectURL(this.src);
			}

			// ページにimg要素を挿入して画像ファイルを表示
			var div_element = document.getElementById('file_viewer');
			div_element.appendChild(img_element);
		}
	});
});


var editor = new tui.Editor({
    el: document.querySelector('#editSection'),
    previewStyle: 'vertical',
    height: '300px',
    initialEditType: 'markdown',
});


$(function($){
	$('#button1').on('click', function() {
		console.log(1);
		const inputTitle = document.getElementById('inputTitle');
		const inputCategory = document.getElementById('inputCategory');
		const published = document.getElementById('published');
		const userId = document.getElementById('userId');
		const contents = editor.getValue();
		$.ajax({
			url : "/posts" ,
			type : "post",
			dataType :"text",
			data: {
				title : inputTitle.value ,
				tagCategory : inputCategory.value ,
				content : contents ,
				is_published : published.value ,
				user_id : userId.value ,
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
			},
		})
		// Ajaxリクエスト成功時の処理
		.done(function(data) {
			alert('成功');
			console.log(data);
		})
		// Ajaxリクエスト失敗時の処理
		.fail(function(jqXHR,textStatus, errorThrown) {
			// 通信失敗時の処理
			alert('ファイルの取得に失敗しました。');
			console.log("ajax通信に失敗しました");
			console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
			console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラー
			console.log("errorThrown    : " + errorThrown.message); // 例外情報
			console.log(jqXHR.responseJSON);
			//console.log("URL            : " + url);
		});
	});
});
