<?php
/**
 * 済み　テンプレートのディレクトリをを自動認識して、ラジオボタンに反映
 * 済み　テンプレートのラジオボタンをサムネイルにする
 * 済み　プレビューエリアにテンプレートを表示
 * 済み　プレビューエリアに入力した情報を表示
 * 済み　出力ボタンで画像出力
 * 済み　テンプレートのラジオボタンをdisplay:noneする
 * テキストの文字色、フォントの変更
 * 済み　プレビューエリアに入力した情報を表示してから、更新するときに以前の情報を初期化
 * 済みQRコードの埋め込み
 * 
 * 
 * 参考URL
 * https://qiita.com/magaya0403/items/e9dc141436376532b568
 * 
 */

 // /img/templateディレクトリに置かれた画像を自動認識して、テンプレートリストに加える
$template_list = array();
foreach(glob('./img/template/{*.jpg,*.png,*.gif}',GLOB_BRACE) as $file){
  if(is_file($file)){
      $template_list[] = $file;
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>バーチャル背景ジェネレーター</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
  </head>
  <body>

    <div class="container" id="app">
      <div class="row">
        <div class="py-5 text-center">
          <img class="d-block mx-auto mb-4" src="./img/logo.png" alt="" width="300" height="200">
          <h2>作成フォーム</h2>
          <p class="lead">バーチャル背景をカスタマイズするために必要な項目を入力して、出力すると画像が書き出されます</p>
        </div>
        <!-- プレビュー_start -->
        <div class="col-md-6 order-md-2 mb-4">
          <h4 class="mb-3">プレビュー</h4>
          <div id="preview-area">
            <canvas id="canvas" class="img-canvas" width="640" height="360" ref="canvas"></canvas>
            <div id=qr-hidden></div>
          </div>
        </div>
        <!-- プレビュー_end -->
        <div class="col-md-6 order-md-1">
          <form class="needs-validation" novalidate="">

            <!-- テンプレート_start -->
            <h4 class="mb-3">テンプレート</h4>
            <div class="d-block my-3">
              <?php foreach($template_list as $k => $v): ?>
                <div class="custom-control custom-radio">
                  <input id="template<?php echo htmlspecialchars($k+1); ?>" name="template" type="radio" class="custom-control-input" required="" value="<?php echo htmlspecialchars($v); ?>" v-model="image">
                  <label class="" for="template<?php echo htmlspecialchars($k+1); ?>">
                    <img src="<?php echo htmlspecialchars($v); ?>" width="100" height="">
                  </label>
                </div>
              <?php endforeach; ?>
              <div id="template_image"></div>
            </div>
            <!-- テンプレート_end -->


            <!-- 入力エリア_start -->
            <hr class="mb-4">
            <h4 class="mb-3">情報</h4>

            <div class="mb-3">
              <label for="conpany">会社名</label>
              <input type="text" class="form-control" id="company" placeholder="株式会社●●" required="" v-model="company">
            </div>

            <div class="mb-3">
              <label for="name">名前</label>
              <input type="text" class="form-control" id="name" placeholder="山田　太郎" required="" v-model="name">
            </div>

            <div class="mb-3">
              <label for="name_en">名前(英語)</label>
              <input type="text" class="form-control" id="name_en" placeholder="Taro Yamada" required="" v-model="name_en">
            </div>

            <div class="mb-3">
              <label for="job">職種 <span class="text-muted">(任意)</span></label>
              <input type="job" class="form-control" id="job" placeholder="職種が入ります" v-model="job">
            </div>

            <div class="mb-3">
              <label for="email">メールアドレス <span class="text-muted">(任意)</span></label>
              <input type="email" class="form-control" id="email" placeholder="you@example.com" v-model="email">
            </div>

            <div class="mb-3">
              <label for="qr">QRコード用リンク <span class="text-muted">(任意)</span></label>
              <input type="qr" class="form-control" id="qr" placeholder="https://www.google.co.jp/" v-model="qr">
            </div>

            <?php /*
            <div class="mb-3">
              <label for="text_font">文字フォント <span class="text-muted">(任意)</span></label>
              <input type="text_font" class="form-control" id="text_font" placeholder="gothic" v-model="text_font">
            </div>

            <div class="mb-3">
              <label for="text_color">文字色 <span class="text-muted">(任意)</span></label>
              <input type="text_color" class="form-control" id="text_color" placeholder="black" v-model="text_color">
            </div>
            */ ?>
            <!-- 入力エリア_end -->

            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="button"　v-on:click="downloadImage()">出力</button>
          </form>
        </div>
      </div>
    </div>
  </body>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="./js/script.js"></script>
</html>
