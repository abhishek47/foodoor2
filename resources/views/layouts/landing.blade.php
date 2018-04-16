<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/images/logo.ico">
    <title>Foodoor.in | When you think of food think foodoor</title>
    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/animsition.min.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet"> </head>

   

<body class="home">
    <div class="site-wrapper animsition" data-animsition-in="fade-in" data-animsition-out="fade-out">
       
         @include('partials.landing._header')
       
         <div class="page-wrapper">
          @yield('content')
         </div>
        

   
        @include('partials._footer')
       
    </div>
    <!--/end:Site wrapper -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/tether.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/animsition.min.js"></script>
    <script src="/js/bootstrap-slider.min.js"></script>
    <script src="/js/jquery.isotope.min.js"></script>
    <script src="/js/headroom.js"></script>
    <script src="/js/foodpicky.min.js"></script> 

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABIlUStLsr84EGUomykEKJeNPIuWbT854&v=3.exp&sensor=false&libraries=places"></script>
        
         

           <script src="/js/locationpicker.jquery.js"></script>
        <script type="text/javascript">
            google.maps.event.addDomListener(window, 'load', function () {
                var places = new google.maps.places.Autocomplete(document.getElementById('txtPlaces'));
                google.maps.event.addListener(places, 'place_changed', function () {

                });
            });


        </script>


            <!-- Modal -->
            <div class="modal fade" id="bulkOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                <form method="POST" action="/bulk-orders">
                   @csrf 
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Fill up the form below for bulk orders</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name"  class="form-control" placeholder="Enter contact name" required="">
                        </div>
                        <div class="form-group">
                            <label>Phone No.</label>
                            <input type="number" step="1" min="0" name="phone" placeholder="Enter contact number"  class="form-control" required="">
                        </div>
                        <div class="form-group">
                            <label>Message</label>
                            <textarea class="form-control" cols="3" name="message" placeholder="Your order purpose/details" required=""></textarea>
                        </div>
                        
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn theme-btn">Send Details</button>
                  </div>
                </form>
                </div>
              </div>
            </div>

        @yield('scripts')
</body>

</html>