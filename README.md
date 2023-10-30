# User Guide: Using Firebase with Yii2 && PHP method
This repository provides a step by step guide on integrating Firebase storage and realtime database with Yii2 and PHP, allowing you to leverage Firebase services in your Yii2 applications. 

## Table of Contents
[Step 1: Prerequisites](https://github.com/KLipYoung/UserGuide-Firebase#step-1-prerequisites)  
[Step 2: Installation](https://github.com/KLipYoung/UserGuide-Firebase#step-2-installation)  
[Step 3: Using Firebase services](https://github.com/KLipYoung/UserGuide-Firebase#step-3-using-firebase-services)  
[Usage: Storage](https://github.com/KLipYoung/UserGuide-Firebase#usage-storage)  
[Usage: Realtime Database](https://github.com/KLipYoung/UserGuide-Firebase#usage-realtime-database)  

### Step 1: Prerequisites
Before getting started, ensure that you have the following:

- PHP installed on your machine.
- Yii2 framework set up.
- Composer package manager installed.
- Firebase account and a Firebase project created.

### Step 2: Installation
To using Firebase with Yii2 && PHP, follow these steps:
1. PHP Version must require over or in PHP 8.1. 
2. Install the required Firebase PHP SDK using Composer. Run the following command:

```
composer require kreait/firebase-php
composer require google/cloud-storage
```
3. google/could-storage is using for upload file to firebase storage.
4. If u found this error "Error: Class "GuzzleHttp\Psr7\HttpFactory" not found", can use "HttpFactory.php" paste in "yourProjectFolder/vendor/guzzlehttp/psr7/src/HttpFactory.php"

### Step 3: Using Firebase services
1. To start using Firebase services, import the necessary Firebase classes in your PHP file:
```
use Kreait\Firebase\Factory;
```

2. Set up the Firebase service account by creating an instance of the ServiceAccount class and providing the path to your Firebase service account JSON file:
```
$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->createStorage();
```

3. Here is the explain and how to get it in some word.
- $serviceAccount
  - Path to your service account json file. Ex: 'c::/path/to/firebase-service-account.json'.
  - **How to get it.**
    1. Go to the Firebase console website https://console.firebase.google.com
    2. Select your Firebase project from the project list (or create a new project if you haven't already).
    3. Click on the gear icon (settings) near the top left corner of the page and select "Project settings".
    4. In the project settings page, navigate to the "Service Accounts" tab.
    5. Under the "Firebase Admin SDK" section, click on the "Generate new private key" button.
- $referencePath
  - **How to get it.**
    1. Go to the Firebase Console website
    2. Select your Firebase project from the project list (or create a new project if you haven't already).
    3. In the left sidebar, click on "Storage" to access Firebase Storage.
    4. You will see the default bucket listed under the "Files" tab. It will be in the format gs://<your-project-id>.appspot.com/. Copy this bucket URL.
    5. To paste in ->getBucket(), need to remove "gs://".
    6. Use the copied URL as the $referencePath value in your code. Replace '<your-project-id>.appspot.com/'.

## Usage: Storage
#### Upload file to firebase "Storage"
```
if ($model->load(Yii::$app->request->post())) {
    $image = UploadedFile::getInstance($model, "imageFile");

    (new \Kreait\Firebase\Factory())
    ->withServiceAccount($serviceAccount)
    ->createStorage()
    ->getBucket($referencePath)
    ->upload(
        fopen($image->tempName, 'r'), // your image tempName, "r" = read-only
        ['name' => 'Test/2023/abcdefg.png'] // upload to firebase file path and name and extension, if folder name not exist, will auto create folder
    );
}
```

#### View image file from firebase "Storage"
```
$database = (new \Kreait\Firebase\Factory())
            ->withServiceAccount($serviceAccount)
            ->createStorage()
            ->getBucket($referencePath)
            ->object('practice/abcdefg.png') // file path and name
            ->signedUrl(new \DateTime('+1 hour')); // set image authorized time
echo "<img src='$database' alt='Image'>";
var_dump($database);
```

#### Get and View all folder and file name in firebase "Storage"
```
$database = (new \Kreait\Firebase\Factory())
            ->withServiceAccount($serviceAccount)
            ->createStorage()
            ->getBucket($referencePath)
            ->objects(['prefix' => 'practice']); // Specified folder path and name, if '', will display all folder and file
foreach ($database as $oneDatabase) {
   $fileNames[] = $oneDatabase->name();
}
var_dump($fileNames);
```

#### Download file to you computer from firebase "Storage"
```
(new \Kreait\Firebase\Factory())
->withServiceAccount($serviceAccount)
->createStorage()
->getBucket($referencePath)
->object('practice/abcdefg.pn') // file name (need to know from firebase storage)
->downloadToFile('C:\to\path\abcdefg.png'); // insert the path where you want to download to
```

## Usage: Realtime Database
#### Insert data to Firebase "Realtime Database"
```
$data = [
            'name' => 'Hello',
            'email' => 'hello@gmail.com',
            'age' => 30,
        ];
// ->set($data) = Insert data to specified path
// ->push($data) = Insert data with auto generated unique key
$database = (new \Kreait\Firebase\Factory())
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri($referencePath)
            ->createDatabase()->getReference('practice') // insert to specified array
            ->set($data);
var_dump($database);
```

#### Get data from Firebase "Realtime Database"
```
$database = (new \Kreait\Firebase\Factory())
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri($referencePath)
            ->createDatabase()->getReference('practice')->getValue(); // get the data from practice array
var_dump($database);
```

#### Modify specified data to Firebase "Realtime Database"
```
$data = 'Hello 2';

(new \Kreait\Firebase\Factory())
->withServiceAccount($serviceAccount)
->withDatabaseUri($referencePath)->getReference('practice')
->getChild('name') // modified key "name" from "Hello" to "Hello 2"
->set($data);
```

#### Remove specified data to Firebase "Realtime Database"
```
(new \Kreait\Firebase\Factory())
->withServiceAccount(Yii::getAlias('@webroot') .'/../.firebase/'. $firebaseModel['serviceAccount'])
->withDatabaseUri($firebaseModel['path'])
->createDatabase()->getReference('practice/user1')
->getChild('name') // remove key "name"
->remove();
```

## Documentation  
- [Firestore Guide](https://firebase-php.readthedocs.io/en/stable/cloud-storage.html)
- [Realtime Guide](https://firebase-php.readthedocs.io/en/stable/realtime-database.html)

## Conclusion
By following these steps, you have successfully integrated Firebase with your Yii2 and PHP application. Now you can leverage Firebase services in your projects, such as the Realtime Database and Storage. Thank you 
