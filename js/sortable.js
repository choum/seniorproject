$( function() {
  $( "#sortable" ).sortable();
  $( ".box" ).resizable({
    grid: 600,
    helper: "ui-resizable-helper",
    start: function (event, ui) {
        if (typeof ui.element.next()[0] != 'undefined')
            this.sizeNeighbor = ui.element.next()[0].getAttribute('data-size');
        this.sizeself = ui.element[0].getAttribute('data-size');
        this.ctss = ui.originalSize.width;
  },
  resize: function (event, ui) {
      // then simply subtract it!
      if(this.ctss > ui.size.width)
      {
          if(this.sizeself > 2)
          {
            $(ui.element[0]).removeClass("col-"+this.sizeself).attr('data-size',(--this.sizeself)).addClass('col-'+this.sizeself);
            if (ui.element.next()[0] != 'undefined')
              $(ui.element.next()[0]).removeClass('col-'+this.sizeNeighbor).attr('data-size',(++this.sizeNeighbor)).addClass('col-'+this.sizeNeighbor);
          }
      }
    else {
        if(this.sizeself < 12)
        {
            $(ui.element[0]).removeClass("col-"+this.sizeself)
              .attr('data-size',(++this.sizeself)).addClass("col-"+this.sizeself);
            if (ui.element.next()[0] != 'undefined')
              $(ui.element.next()[0]).removeClass('col-'+this.sizeNeighbor)
               .attr('data-size',(--this.sizeNeighbor)).addClass("col-"+this.sizeNeighbor);
        }
    }
    this.ctss = ui.size.width;
  },
  stop: function(event, ui) {
      delete this.ctss;
      delete this.sizeself;
      delete this.sizeNeighbor;
  }

  });
});
