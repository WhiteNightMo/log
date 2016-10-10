/**
 * Created by xiaomo on 2016/9/2.
 */
$(document).ready(function () {
    var contentWidth = $(".log-content").width();
    var widthArr = [];
    // 页面加载调整图片大小
    $(".log-content img").each(function () {
        var imgWidth = $(this).width();
        widthArr[widthArr.length] = imgWidth;

        if (imgWidth > contentWidth) {
            var imgHeight = $(this).height();
            var ratio = contentWidth / imgWidth;

            $(this).css("width", contentWidth);
            $(this).css("height", imgHeight * ratio);
        }
    });

    // 监听页面大小变化
    $(window).resize(function () {
        var contentWidth = $(".log-content").width();
        $(".log-content img").each(function (index) {
            var imgWidth = $(this).width();
            if (imgWidth > contentWidth) {
                var imgHeight = $(this).height();
                var ratio = contentWidth / imgWidth;

                $(this).css("width", contentWidth);
                $(this).css("height", imgHeight * ratio);
            } else if (contentWidth < widthArr[index]) {
                var imgHeight = $(this).height();
                var ratio = contentWidth / imgWidth;

                $(this).css("width", contentWidth);
                $(this).css("height", imgHeight * ratio);
            }
        });
    });
});