<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HttpTest extends TestCase
{
    /**
     * test send request GET to /post and get json data success
     *
     * @return void
     */
    public function testMethodGetActionPostReturnJsonOk()
    {
    	$rs = $this->json('get','/post');    	
        $rs->assertJson(['code' => 1]);
    }
    
    /**
     * send PUT request to /post , empty title , empty body , return json code 2
     * @return void
     */
    public function testMethodPutActionPostReturnJsonCode2Ok()
    {
    	$rs = $this->json('put','/post');
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send PUT request to /post , title = bicycle , empty body , return json code 2
     * @return void
     */
    public function testMethodPutActionPostEmptyBodyReturnJsonCode2Ok()
    {
    	$rs = $this->json('put','/post',['title' => 'bicycle']);
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send PUT request to /post , title = bicycle ,  body = bicycle , return json code 1
     * @return void
     */
    public function testMethodPutActionPostTitleBodyReturnJsonCode1Ok()
    {
    	$rs = $this->json('put','/post',['title' => 'bicycle','body'=>'bicycle']);
    	$rs->assertJson(['code' => 1]);
    }
    
    /**************************************/
    
    /**
     * send POST request to /post , empty id , return json code 2
     * @return void
     */
    public function testMethodPostActionPostEmptyIdReturnJsonCode2Ok()
    {
    	$rs = $this->json('post','/post');
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send POST request to /post , empty title , empty body , return json code 2
     * @return void
     */
    public function testMethodPostActionPostEmptyTitleEmptyBodyReturnJsonCode2Ok()
    {
    	$rs = $this->json('post','/post');
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send POST request to /post , empty body , return json code 2
     * @return void
     */
    public function testMethodPostActionPostEmptyBodyReturnJsonCode2Ok()
    {
    	$rs = $this->json('post','/post',['title' => 'bicycle']);
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send DELETE request to /post , empty id , return json code 2
     * @return void
     */
    public function testMethodDeleteActionPostEmptyIdReturnJsonCode2Ok()
    {
    	$rs = $this->json('post','/post');
    	$rs->assertJson(['code' => 2]);
    }
    
    /***************************************************/
    /**
     * test send request GET to /tag and get json data success
     *
     * @return void
     */
    public function testMethodGetActionTagReturnJsonOk()
    {
    	$rs = $this->json('get','/tag');
    	$rs->assertJson(['code' => 1]);
    }
    
    /**
     * send PUT request to /tag , empty name , return json code 2
     * @return void
     */
    public function testMethodPutActionTagEmptyNameReturnJsonCode2Ok()
    {
    	$rs = $this->json('put','/tag');
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send PUT request to /tag , name='newcar' , return json code 1
     * @return void
     */
    public function testMethodPutActionTagReturnJsonCode1Ok()
    {
    	$rs = $this->json('put','/tag',['name'=>'newcar']);
    	$rs->assertJson(['code' => 1]);
    }
    
    /**
     * send POST request to /tag ,empt name , return json code 2
     * @return void
     */
    public function testMethodPostEmptyNameActionTagReturnJsonCode2Ok()
    {
    	$rs = $this->json('post','/tag');
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send DELETE request to /tag ,empty name , return json code 2
     * @return void
     */
    public function testMethodDeleteEmptyNameActionTagReturnJsonCode2Ok()
    {
    	$rs = $this->json('delete','/tag');    	
    	$rs->assertJson(['code' => 2]);
    }
    
    /********** tagpost *****************************************/
    
    /**
     * send POST request to /tagpost ,empty tag name , empty postId , return json code 2
     * @return void
     */
    public function testMethodPostEmptyTagNameEmptyPostIdActionTagPostReturnJsonCode2Ok()
    {
    	$rs = $this->json('post','/tagpost');
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send POST request to /tagpost , tag name=newcar , empty postId , return json code 2
     * @return void
     */
    public function testMethodPostEmptyPostIdActionTagPostReturnJsonCode2Ok()
    {
    	$rs = $this->json('post','/tagpost',['tagName'=>'newcar']);
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send POST request to /tagpost , tag name=newcar , postId = 1 , return json code 1
     * @return void
     */
    public function testMethodPostdActionTagPostReturnJsonCode1Ok()
    {
    	$rs = $this->json('post','/tagpost',['tagName'=>'newcar','postId'=>1]);
    	$rs->assertJson(['code' => 1]);
    }
    
    /************* showpost */
    /**
     * send POST request to /showpost ,empty tags  return json code 2
     * @return void
     */
    public function testMethodPostEmptyTagsActionShowPostReturnJsonCode2Ok()
    {
    	$rs = $this->json('post','/showpost');
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send POST request to /showpost , tags=newcar  return json code 1
     * @return void
     */
    public function testMethodPostActionShowPostReturnJsonCode1Ok()
    {
    	$rs = $this->json('post','/showpost',['tags'=>'newcar']);
    	$rs->assertJson(['code' => 1]);
    }
    
    /************* postcount */
    /**
     * send POST request to /postcount ,empty tags  return json code 2
     * @return void
     */
    public function testMethodPostEmptyTagsActionPostCountReturnJsonCode2Ok()
    {
    	$rs = $this->json('post','/postcount');
    	$rs->assertJson(['code' => 2]);
    }
    
    /**
     * send POST request to /showpost , tags=newcar  return json code 1
     * @return void
     */
    public function testMethodPostActionPostCountReturnJsonCode1Ok()
    {
    	$rs = $this->json('post','/postcount',['tags'=>'newcar']);
    	$rs->assertJson(['code' => 1]);
    }
}
