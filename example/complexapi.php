<?php

// slightly complex API example. 
// This service listens to 2 REST paths: 'example' and 'sample'. other paths are not permitted.

// The sample path accepts only GET and PUT methods and the example path accepts 
// only GET and POST methods.
//
// All other method and path combinations will fail with a 405 response error

include('include/RESTling/contrib/Restling.auto.php');

class ComplexAPIExample extends RESTling 
{    
    private $apimode = 'default';
    
    protected function validateURI() 
    {
        parent::validateURI(); // this makes the path_info property available.
        
        // the API is defined via the path_info. 
        $api = explode('/', $this->path_info);
        
        // note that one slash splits the string into 2 partitions. The first element is always empty.
        // if the second element is not empty we can use it. If the string has more partitions, the request.
        // will get rejected.
        
        if (count($api) > 2) 
        {
            // this example allows only one level of the path_info depth.
            $this->status = RESTling::BAD_URI;   
            return;
        }
        
        if (count($api) == 2 && !empty($api[1]))
        {
            // if there is exactly 1 path_info level, then we use that for determing the 'service mode'.
            $this->apimode = $api[1];
        }
    }
    
    protected function prepareOperation() 
    {
        // combine the method and the apimode to determine which operation to run
        $this->operation = strtolower($this->method) . '_' . strtolower($this->apimode);
        
        // after this call the "operation" property contains the name of the operation to run.
    }
    
    // Operation definitions below
    
    // this operation is called when no path parameters are available
    protected function get_default()
    {
        $this->data = 'get default ok';        
    }
    
    // GET /example
    protected function get_example() 
    {
        $this->data = 'get example ok';
    }
    
    // POST /example
    protected function post_example()
    {
        $this->data = 'post example ok';        
    }
    
    // GET /sample
    protected function get_sample() 
    {
        $this->data = 'get sample ok';
    }
    
    // PUT /sample
    protected function put_sample() 
    {
        $this->data = 'put sample ok';
    }
}

$service = new ComplexAPIExample();

$service->run();
?>