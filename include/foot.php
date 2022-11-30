<div class="wrapper">
		
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".delete-record").click(function(e){
				e.preventDefault();
				
				var confirmBox = confirm('De verdad quiere borrarlo?');
				
				if(confirmBox == true)
				{	
					var getHref = $(this).attr('href');
					window.location.href=getHref;
				}
							
			});
		});
		
		
	</script>	
    
    <h6 > Sitio realizado en Argentina - 2022 </h6>
   
    </div>
</body>
</html>