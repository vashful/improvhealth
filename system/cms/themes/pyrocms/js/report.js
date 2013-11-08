$(document).ready(function() {
    $("#rpt_preview").click(function(){
        var ppoty = $("#q1_ppoty").val();
        if((ppoty.match(/^\d+$/)) || (ppoty.match(/^\d+\.\d+$/)))
        {
            $("#frm_show").attr("action",base_url+"admin/rhu_q1/preview/");
            $("#frm_show").submit();          
            $("#frm_show").attr("action",base_url+"admin/rhu_q1/show/");
        }
        else
        {
            $(".number-results").html("Please enter a value for \"Projected Population of the Year\"");
            $(".number-results").css("display","block");
        }
    });    
    $("#generate_rhu_q1").click(function(){
        var ppoty = $("#q1_ppoty").val();
        if((ppoty.match(/^\d+$/)) || (ppoty.match(/^\d+\.\d+$/)))
        {
            $("#frm_show").attr("action",base_url+"admin/rhu_q1/show/");
            $("#frm_show").submit();
        }
        else
        {
            $(".number-results").html("Please enter a value for \"Projected Population of the Year\"");
            $(".number-results").css("display","block");
        }
    });   
    $("#generate_rhu_q1_dl").click(function(){
        var ppoty = $("#q1_ppoty").val();
        if((ppoty.match(/^\d+$/)) || (ppoty.match(/^\d+\.\d+$/)))
        {
            $("#frm_show").attr("action",base_url+"admin/rhu_q1/show_pdf/");
            $("#frm_show").submit();
            $("#frm_show").attr("action",base_url+"admin/rhu_q1/show/");
        }
        else
        {
            $(".number-results").html("Please enter a value for \"Projected Population of the Year\"");
            $(".number-results").css("display","block");
        }
    });   
});