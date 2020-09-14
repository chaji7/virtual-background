
new Vue({
  el: '#app',
  data: {
    'image':null,
    'company':null,
    'name':null,
    'name_en':null,
    'job':null,
    'email':null,
    'qr':null,
    'text_color':null,
    'text_font':null,
    'flg':{
      'image':null,
      'company':null,
      'name':null,
      'name_en':null,
      'job':null,
      'email':null,
      'qr':null,
      'text_color':null,
      'text_font':null
    }
  },
  mounted: function(){

  },
  watch:{
    // canvasにimageをセット
    image: function(){
      if(this.flg['company']){
        this.allRedraw();
      }else{
        this.setImage();
      }
    },
    // 各項目
    company: function(){
      if(this.flg['company']){
        this.allRedraw();
      }else{
        this.drawText('company', 30, 20);
      }
    },
    name: function(){
      if(this.flg['name']){
        this.allRedraw();
      }else{
        this.drawText('name', 30, 100);
      }
    },
    name_en: function(){
      if(this.flg['name_en']){
        this.allRedraw();
      }else{
        this.drawText('name_en', 30, 150, '16px gothic');
      }
    },
    job: function(){
      if(this.flg['job']){
        this.allRedraw();
      }else{
        this.drawText('job', 30, 200, '16px gothic');
      }
    },
    email: function(){
      if(this.flg['email']){
        this.allRedraw();
      }else{
        this.drawText('email', 30, 300, '16px gothic');
      }
    },
    qr: function(){
      if(this.qr==''){
        this.flg['qr'] = false;
      }else{
        this.flg['qr'] = true;
      }
      this.allRedraw();
    },
    text_color: function(){
      this.allRedraw();
    },
    text_font: function(){
      this.allRedraw();
    }
  },
  methods:{
    setImage() {
      // canvas準備
      var canvas = document.getElementById('canvas');
      var ctx = canvas.getContext('2d');

      // 画像読み込み
      const chara = new Image();
      //chara.src = "/img/template/ataru.jpg";  // 画像のURLを指定
      chara.src = this.image; // 画像のURLを指定
      chara.addEventListener('load', function() {
        ctx.drawImage(chara, 0, 0, 640, 360);
      });
      this.flg['image'] = true;
    },
    drawText(id, x, y, font){
      var canvas = document.getElementById('canvas');
      var ctx = canvas.getContext('2d');
      var text = document.getElementById(id);
      //ctx.clearRect(0, 0, x, y);
      //文字のスタイルを指定
      if(!font){
        ctx.font = '32px gothic';
      }else{
        ctx.font = font;
      }
      ctx.fillStyle = '#404040';
      //文字の配置を指定（左上基準にしたければtop/leftだが、文字の中心座標を指定するのでcenter
      ctx.textBaseline = 'top';
      ctx.textAlign = 'left';
      //座標を指定して文字を描く（座標は画像の中心に）
      ctx.fillText(text.value, x, y);
      //対象データが空かどうかフラグに代入(再描画用)
      if(text.value == ''){
        this.flg[id] = false;
      }else{
        this.flg[id] = true;
      }
    },
    downloadImage(){
      let link = document.createElement("a");
      link.href = canvas.toDataURL("image/jpg");
      link.download = "test.jpg";
      link.click();
    },
    allRedraw(){
      var canvas = document.getElementById('canvas');
      var ctx = canvas.getContext('2d');
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.beginPath();
      // 画像読み込み
      
      const chara = new Image();
      chara.src = this.image; // 画像のURLを指定
      var vue_data = this;
      chara.addEventListener('load', function() { // 画像の指定が終わってから、canvasに描画スタートしないと、テキストが画像より先に来てしまう
        ctx.drawImage(chara, 0, 0, 640, 360);
        vue_data.drawText('company', 30, 20);
        vue_data.drawText('name', 30, 100);
        vue_data.drawText('name_en', 30, 150, '16px gothic');
        vue_data.drawText('job', 30, 200, '16px gothic');
        vue_data.drawText('email', 30, 300, '16px gothic');
        // QRコード生成
        if(vue_data.flg['qr']){
          var qrtext = vue_data.qr;
          var utf8qrtext = unescape(encodeURIComponent(qrtext));
          $("#qr-hidden").html("");
          $("#qr-hidden").qrcode({
            text:utf8qrtext,
            width:64,
            height:64
          });
          qr_url = $("#qr-hidden canvas")[0].toDataURL();
          const qr_image = new Image();
          qr_image.src = qr_url;
          qr_image.addEventListener('load', function() {
            ctx.drawImage(qr_image, 550, 30);
          });
          
        }
      });
      this.flg['image'] = true;
    }

  }
});

