(function() {
  function buildLikeListener(type){
    return function(e){
      var heartSymbol = "&#10084;";
      var link = $(this);
      var oldContent = link.html();
      var timerRef = null;
      var frame = 0;
      var objectId = link.attr("data-" + type + "-id") ;
      var success = function(res){
        if ( timerRef ){
          clearInterval(timerRef);
        }
        if ( res.result && res.result.hasOwnProperty('like_count')){
          link.html(heartSymbol + " " + res.result.like_count);
          return;
        }
        link.html(oldContent);
      };
      var loader = [
        "\\","|", "/", "-",
      ];
      var beforeSend = function(req){
        timerRef = setInterval(function(){
          link.html(heartSymbol + " " + loader[frame++]);
          if ( frame === loader.length - 1 ){
            frame = 0;
          }
        }, 50);
      };
      var error = function(err){
        if ( timerRef ){
          clearInterval(timerRef);
        }
        link.html(oldContent);
      };
      if ( objectId ){
        $.ajax({
          type: "POST",
          url: "index.php",
          data: {
            controller: "rest-" + type,
            action: "like",
            id: objectId
          },
          beforeSend: beforeSend,
          success: success,
          error: error,
          dataType: "json"
        });
      }
    }
  }

  var listeners = {
    likeTopic: buildLikeListener("topic"),
    likeComment: buildLikeListener("comment")
  };

  $(document).ready(function(){
    $("a").each( function(index, ele){
      ele = $(ele);
      var listenKey = ele.attr('data-action');
      if ( listenKey ){
        if ( typeof listeners[listenKey] === "function" ){
          ele.click(listeners[listenKey]);
        }
      }
    } );
  } );
} )();
