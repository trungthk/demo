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
            line-height: 1.5em;       /* chiều cao mỗi dòng */
            max-height: calc(1.5em * 3); /* 3 dòng (có thể thay bằng JS đọc data-line) */
            word-break: break-word;
            white-space: pre-wrap;      /* cho phép xuống dòng */
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body">
                    <div class="description" data-line="3">{{$data}}</div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        // Helper: escape HTML (an toàn trước XSS nếu bạn hiển thị bằng .html())
        function escapeHtml(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        // Helper: trả về mảng grapheme clusters (sử dụng Intl.Segmenter nếu có, ngược lại fallback)
        function splitGraphemes(str) {
            // modern browsers: precise grapheme clusters
            if (typeof Intl !== 'undefined' && typeof Intl.Segmenter === 'function') {
                try {
                    return Array.from(new Intl.Segmenter(undefined, { granularity: 'grapheme' }).segment(str), s => s.segment);
                } catch (e) {
                    // fallthrough to fallback
                }
            }

            // fallback: best-effort grouping (surrogate pairs + variation selector + combining marks + ZWJ sequences + skin-tone)
            const combining = /[\u0300-\u036F\u1AB0-\u1AFF\u1DC0-\u1DFF\u20D0-\u20FF\uFE20-\uFE2F]/;
            const VS16 = '\uFE0F';
            const ZWJ = '\u200D';

            const isHighSurrogate = ch => ch >= '\uD800' && ch <= '\uDBFF';
            const isLowSurrogate = ch => ch >= '\uDC00' && ch <= '\uDFFF';

            const codePointAt = (s, i) => s.codePointAt(i);

            const isSkinTone = cp => (cp >= 0x1F3FB && cp <= 0x1F3FF);

            const res = [];
            for (let i = 0; i < str.length; i++) {
                let ch = str[i];

                // handle surrogate pair for current char
                if (isHighSurrogate(ch) && i + 1 < str.length && isLowSurrogate(str[i + 1])) {
                    ch += str[i + 1];
                    i++;
                }

                // attach subsequent combining marks
                while (i + 1 < str.length && combining.test(str[i + 1])) {
                    ch += str[++i];
                }

                // variation selector (FE0F)
                if (i + 1 < str.length && str[i + 1] === VS16) {
                    ch += str[++i];
                }

                // skin-tone modifier directly after base emoji (U+1F3FB..U+1F3FF)
                if (i + 1 < str.length) {
                    let nextCp = str.codePointAt(i + 1);
                    if (isSkinTone(nextCp)) {
                        // next is skin tone (may be surrogate pair)
                        if (isHighSurrogate(str[i + 1]) && i + 2 < str.length && isLowSurrogate(str[i + 2])) {
                            ch += str[i + 1] + str[i + 2];
                            i += 2;
                        } else {
                            ch += str[++i];
                        }
                    }
                }

                // handle ZWJ sequences: base + ZWJ + next (repeat)
                while (i + 1 < str.length && str[i + 1] === ZWJ) {
                    ch += str[++i]; // add ZWJ
                    if (i + 1 < str.length) {
                        // attach next (could be surrogate pair)
                        if (isHighSurrogate(str[i + 1]) && i + 2 < str.length && isLowSurrogate(str[i + 2])) {
                            ch += str[i + 1] + str[i + 2];
                            i += 2;
                        } else {
                            ch += str[++i];
                        }
                        // variation selector / combining marks after the next
                        while (i + 1 < str.length && combining.test(str[i + 1])) {
                            ch += str[++i];
                        }
                        if (i + 1 < str.length && str[i + 1] === VS16) {
                            ch += str[++i];
                        }
                    }
                }

                res.push(ch);
            }
            return res;
        }

        // Optional: kiểm tra một grapheme có phải emoji không (dùng Unicode property nếu có, fallback bằng khoảng range)
        function isEmojiGrapheme(g) {
            try {
                return /\p{Emoji}/u.test(g);
            } catch (e) {
                // fallback: check common emoji ranges by codepoint
                for (let i = 0; i < g.length; i++) {
                    const cp = g.codePointAt(i);
                    if (
                        (cp >= 0x1F300 && cp <= 0x1FAFF) || // pictographs, emoticons, etc
                        (cp >= 0x1F600 && cp <= 0x1F64F) || // emoticons
                        (cp >= 0x2600 && cp <= 0x26FF) ||
                        (cp >= 0x2700 && cp <= 0x27BF) ||
                        (cp >= 0x1F900 && cp <= 0x1F9FF) ||
                        (cp >= 0x1F000 && cp <= 0x1FFFF)
                    ) return true;
                }
                return false;
            }
        }

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