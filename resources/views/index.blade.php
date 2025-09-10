<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .description {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.5em;
            word-break: break-word;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body">
                    <div class="description" data-line="5">{{$data}}</div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        // Debounce helper (resize)
        function debounce(fn, wait) {
            var t;
            return function () {
                clearTimeout(t);
                t = setTimeout(fn, wait || 100);
            };
        }

        // Main: optimized truncate with grapheme-aware binary search
        function truncateDescription() {
            $(".description").each(function () {
                var $el = $(this);

                // Lưu nội dung gốc (bao gồm HTML)
                var originalHtml = $el.data("original");
                if (!originalHtml) {
                    originalHtml = $el.html();
                    $el.data("original", originalHtml);
                }
                $el.html(originalHtml);

                var line = parseInt($el.data("line")) || 3;
                var lineHeight = parseFloat($el.css("line-height")) || 20;
                var maxHeight = line * lineHeight;

                // Nếu không vượt quá chiều cao thì không cắt
                if ($el[0].scrollHeight <= maxHeight) {
                    return;
                }

                // Hàm cắt an toàn DOM (giữ nguyên tag HTML)
                function truncateNode(node, maxLen) {
                    if (node.nodeType === Node.TEXT_NODE) {
                        let txt = node.textContent;
                        console.log('txt: ', txt);

                        // Binary search trên text node
                        let low = 0, high = txt.length, mid;
                        while (low < high) {
                            mid = Math.floor((low + high) / 2);
                            node.textContent = txt.substring(0, mid) + "...";
                            if ($el[0].scrollHeight > maxHeight) {
                                high = mid - 1;
                            } else {
                                low = mid + 1;
                            }
                        }

                        // Cắt tại vị trí an toàn (xử lý emoji cuối cùng)
                        let finalText = txt.substring(0, low - 1);
                        if (/\p{Extended_Pictographic}/u.test(finalText.slice(-1))) {
                            finalText = finalText.slice(0, -1);
                            console.log('finalText: ', finalText);
                        }
                        console.log('finalText Emoji: ', finalText);
                        node.textContent = finalText + "...";
                        return true; // dừng lại
                    } else if (node.nodeType === Node.ELEMENT_NODE) {
                        for (let i = node.childNodes.length - 1; i >= 0; i--) {
                            if (truncateNode(node.childNodes[i], maxLen)) {
                                while (i + 1 < node.childNodes.length) {
                                    node.removeChild(node.childNodes[i + 1]);
                                }
                                return true;
                            }
                        }
                    }
                    return false;
                }

                // Clone nội dung gốc vào DOM tạm
                let clone = $el.clone(true).get(0);
                
                $el.empty().append(clone.childNodes);

                // Thực hiện cắt
                truncateNode($el[0], 0);
            });
        }

        // gọi khi load và khi resize (debounce để tránh gọi quá nhiều lần trên resize)
        $(document).ready(truncateDescription);
        $(window).on('resize', debounce(truncateDescription, 120));

    </script>
</body>
</html>