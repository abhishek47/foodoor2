<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RestaurantRequest as StoreRequest;
use App\Http\Requests\RestaurantRequest as UpdateRequest;

use App\User;
use Illuminate\Support\Facades\Hash;

class RestaurantCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Restaurant');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/restaurants');
        $this->crud->setEntityNameStrings('restaurant', 'restaurants');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        if(auth()->user()->isRestaurant())
        {

             $this->crud->denyAccess('list');   

        }


         $this->crud->addColumns([
            ['name' => 'name', 'label' => 'Name'],
            ['name' => 'area', 'label' => 'Area'],
            ['name' => 'pincode', 'label' => 'Pincode'],
            ['name' => 'cityName', 'label' => 'City'],
        ]);


         $this->crud->addField(['name' => 'name', 'label' => 'Name <span style="color: red;">*</span>',  'tab' => 'General']);

        if(!auth()->user()->isRestaurant())
        {
              $this->crud->addField(
                
                [  
                    'name' => 'rating',
                    'label' => 'Restaurant Rating',
                    'type' => 'number',
                    'attributes' => ["step" => 0.5, "min" => 1, "max" => 5],
                    'tab' => 'General'
                ]

            );

        }

        $this->crud->addFields([

              [   // Browse
                    'name' => 'logo',
                    'label' => 'Restaurant Logo',
                    'type' => 'browse',
                    'tab' => 'General'
                ],

            
             
             
             ['name' => 'slug', 'label' => 'Slug', 'type' => 'hidden', 'value' => 'a'],
             
             [       
                'label' => 'Cuisines <span style="color: red;">*</span>',
                'type' => 'select2_multiple',
                'name' => 'cuisines', 
                'entity' => 'cuisines', 
                'attribute' => 'name',
                'model' => "App\Models\Cuisine", 
                'pivot' => true, 
                'tab' => 'General'
             ],
             
             ['name' => 'location', 'label' => 'Google Map Location <span style="color: red;">*</span>', 'type' => 'location', 'tab' => 'Location'],
            
             ['name' => 'area', 'label' => 'Area <span style="color: red;">*</span>', 'tab' => 'Location'],
            
             ['name' => 'pincode', 'label' => 'Pincode <span style="color: red;">*</span>', 'type' => 'number', 'attributes' => ["step" => 1, "maxlength"=>6, "min" => 1], 'tab' => 'Location'] ,
            
             [  // Select2
               'label' => 'City <span style="color: red;">*</span>',
               'type' => 'select2',
               'name' => 'city_id', // the db column for the foreign key
               'entity' => 'city', // the method that defines the relationship in your Model
               'attribute' => 'name', // foreign key attribute that is shown to user
               'model' => "App\Models\City" // foreign key model
               , 'tab' => 'Location'
             ],

             ['name' => 'open_time', 'label' => 'Open Time <span style="color: red;">*</span>', 'type' => 'time', 'tab' => 'General'],

             ['name' => 'close_time', 'label' => 'Close Time <span style="color: red;">*</span>', 'type' => 'time', 'tab' => 'General'],

             ['name' => 'min_price', 'label' => 'Minimum Price (2 person)', 'type' => 'number' , 'tab' => 'General'],

             ['name' => 'contact_name', 'label' => 'Contact Person <span style="color: red;">*</span>', 'tab' => 'Account'],

             ['name' => 'contact_email', 'label' => 'Account Email (Email to access restaurant panel | Default Password : password) <span style="color: red;">*</span>', 'type' => 'email', 'tab' => 'Account'],

             ['name' => 'contact_phone', 'label' => 'Contact Phone <span style="color: red;">*</span>', 'type' => 'number', 'attributes' => ["step" => 1, "maxlength"=>10, "min" => 1], 'tab' => 'Account'],

             ['name' => 'website', 'label' => 'Website Link' , 'tab' => 'General'],

             ['name' => 'bank_name', 'label' => 'Bank Name' , 'tab' => 'Banking'],

             ['name' => 'bank_ifsc', 'label' => 'Bank IFSC Code', 'tab' => 'Banking'],

             ['name' => 'bank_acc_no', 'label' => 'Bank Account No.', 'type' => 'number', 'attributes' => ["step" => 1, "min" => 1], 'tab' => 'Banking'],

             ['name' => 'bank_acc_name', 'label' => 'Bank Account Name', 'tab' => 'Banking'],

             [ // select_from_array
                'name' => 'bank_acc_type',
                'label' => "Savings or Current?",
                'type' => 'select2_from_array',
                'options' => [0 => 'Savings Account', 1 => 'Current Account'],
                'allows_null' => false,
                'default' => 1
                , 'tab' => 'Banking'
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
             ],

        ]);

        
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        

        $user = User::where('email', $this->crud->entry->contact_email)->first();

        if($user != null)
        {
            $this->crud->entry->account_id = $user->id;
        } else {
               $user = User::create([
                'name' => $this->crud->entry->contact_name,
                'email' => $this->crud->entry->contact_email,
                'password' => Hash::make('password'),
            ]);

                 $this->crud->entry->account_id = $user->id;


        }

        $this->crud->entry->slug = str_slug($this->crud->entry->name);

        $this->crud->entry->save();

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        $user = User::where('email', $this->crud->entry->contact_email)->first();

        if($user != null)
        {
            $this->crud->entry->account_id = $user->id;
        } else {

            $user = User::create([
                'name' => $this->crud->entry->contact_name,
                'email' => $this->crud->entry->contact_email,
                'password' => Hash::make('password'),
            ]);

            $this->crud->entry->account_id = $user->id;
        }

         $this->crud->entry->slug = str_slug($this->crud->entry->name);


        $this->crud->entry->save();


        return $redirect_location;
    }
}
