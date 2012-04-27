(function($) {
   $(function() {
       $(".pdfppt-link").each(function() {
           var $this = $(this);
           var fileLocation = $this.attr("href");
           var html = '<iframe class="pdf-ppt-viewer" src="http://docs.google.com/gview?url=' + fileLocation + '&embedded=true" style="width:' + pdfpptWidth + 'px; height:' + pdfpptHeight + 'px;" frameborder="0"></iframe>';

           $(html).insertAfter($this);
           $this.remove();
       });    
   });

})(jQuery);
