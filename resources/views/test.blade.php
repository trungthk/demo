<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clamp Text Stagger Demo</title>
  <style>
    .clamp-text {
        visibility: hidden;      /* Ẩn ban đầu */
        opacity: 0;              /* Làm mờ */
        transform: translateY(5px); /* Trượt nhẹ xuống */
        transition: opacity 0.5s ease, transform 0.5s ease;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        padding: 20px;
    }

    .clamp-text.clamp-ready {
        visibility: visible;      
        opacity: 1;
        transform: translateY(0);

        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

  </style>
</head>
<body>
  <div class="container">
    <div class="row">
        <div class="col-md-12">
            @for ($i = 0; $i < 10; $i++)
                <div class="clamp-text">
                    レギュラー勤務から、シフト希望の方まで大歓迎♪ 頑張った分、しっかり休んでいただく為に、週休2日制度導入しました！ 創業10年以上の老舗デリバリー店です！安心の法人経営！ 働きやすい環境つくりに最善を尽くす会社です！環境、待遇は都内ＮＯ,1クラスです！ 日本一真面目な店長と一緒にこの会社を育てていきませんか。 <店長候補・事務所内勤務スタッフ> お客様からの電話対応、ウェブサイトの更新作業、女の子の管理など店舗運営にかかわる業務です。 弊社のスタッフは９割が未経験からのスタートです！未経験者大歓迎！！弊社スタッフが丁寧に指導いたしますので、ご安心ください。 日給１万円以上可能！ 昇給随時！ Photoshop・Illustratorが扱える方、カメラ撮影が出来る方優遇いたします
                </div>
            @endfor
        </div>
        <div class="col-md-12">
            <img src="https://picsum.photos/2000/1000" alt="big image">
        </div>
    </div>
  </div>

  <script>
    // Sự kiện DOMContentLoaded: chạy ngay khi DOM được parse xong
    document.addEventListener("DOMContentLoaded", () => {
      console.log("✅ DOMContentLoaded:", new Date().toLocaleTimeString());
      const items = document.querySelectorAll(".clamp-text");
      items.forEach((el, i) => {
        setTimeout(() => {
          el.classList.add("clamp-ready");
        }, i * 300); // delay 100ms cho mỗi phần tử
      });
    });

    // Sự kiện load: chạy sau khi tất cả tài nguyên (ảnh, css, js...) được load
    window.addEventListener("load", () => {
      console.log("✅ Window Load:", new Date().toLocaleTimeString());
    //   const items = document.querySelectorAll(".clamp-text");
    //   items.forEach((el, i) => {
    //     setTimeout(() => {
    //       el.classList.add("clamp-ready");
    //     }, i * 300); // delay 100ms cho mỗi phần tử
    //   });
    });
  </script>
</body>
</html>
