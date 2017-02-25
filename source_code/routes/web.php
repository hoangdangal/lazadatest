 <?php
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades;
	use App\Mail;
	use Illuminate\Support\Facades\View;
	use Illuminate\Support\Facades\Cache;
	use function GuzzleHttp\json_decode;
	/*
	 * |--------------------------------------------------------------------------
	 * | Web Routes
	 * |--------------------------------------------------------------------------
	 * |
	 * | Here is where you can register web routes for your application. These
	 * | routes are loaded by the RouteServiceProvider within a group which
	 * | contains the "web" middleware group. Now create something great!
	 * |
	 */
	
	
	
	Route::get ( '/', function () {
		return view ( 'welcome' );
	} );
	


	

	

	
	
/********* API AUTHORIZE ***************/
// Route::post('/oauth/authorize',function(){
	
// });

Route::post('/oauth/token',function(){
	$access_token = '';
	$refresh_token = '';
	$expires_in = 3600; // the number second until the access token expires.
	return response()->json(['access_token' => $access_token , 'refresh_token' => $refresh_token]);
});
/***************************************/
	

/***
 * 	Client ID: 4
 Client secret: zaKAn5EO7GhfZgsUiV4xjwGS5JeWRHf22XDqtIuG
 */
	
/************ client API ******************/	
/***
 * return list clients
 */
Route::get('/oauth/clients',function(){
	
});

/***
 * register client
 */
Route::post('/oauth/clients',function(Request $request){
	// check require name parameter
	// check require redirect parameter
});

/**
 * update client
 */
Route::put('/oauth/clients/{client-id}',function($clientId){
	// check require name parameter
	// check require redirect parameter
});

/****** delete client ***********/
Route::delete('/oauth/clients/{client-id}',function($clientId){
	
});


/********* this section is for client demo **************/

Route::get ( '/redirect', function () {
	$query = http_build_query ( [
			'client_id' => '4',
			'redirect_uri' => 'http://localhost/lazadatest/public/callback',
			'response_type' => 'code',
			'scope' => ''
	] );
	
	return redirect ( 'http://localhost/lazadatest/public/oauth/authorize?' . $query );
} );


Route::get ('/callback', function (Request $request) {
	$http = new GuzzleHttp\Client ();
	$response = $http->post ( 'http://localhost/lazadatest/public/oauth/token', [
			'form_params' => [
					'grant_type' => 'authorization_code',
					'client_id' => 'client-id',
					'client_secret' => 'client-secret',
					'redirect_uri' => 'http://localhost/lazadatest/public/callback',
					'code' => $request->code
			]
	] );
	return json_decode ( $response->getBody (), true );
} );
	