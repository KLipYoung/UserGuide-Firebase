<?php
namespace backend\models\firebase; 

use backend\models\firebase\Firebase;

use Yii;
use Kreait\Firebase\Factory;
use yii\web\UploadedFile;

class FirebaseGuide
{
    // ----------- !! Important !! For Firebase Guide/Example only, no function !!! --------------
    public function realtimeDatabaseGuide()
    {
        // --- Get model from database ---
        $firebaseModel = Firebase::find()->where(['name' => 'MyProject'])->one();

        // --- Get data from Firebase "Realtime Database" ---
        $database = (new \Kreait\Firebase\Factory())
            ->withServiceAccount(Yii::getAlias('@webroot') .'/../.firebase/'. $firebaseModel['serviceAccount'])
            ->withDatabaseUri($firebaseModel['path'])
            ->createDatabase()->getReference('practice')->getValue();
        var_dump($database);

        // --- Insert data to Firebase "Realtime Database" ---
        $data = [
            'name' => 'Hello',
            'email' => 'hello@gmail.com',
            'age' => 30,
        ];
        // ->set($data) = Insert data to specified path
        // ->push($data) = Insert data with auto generated unique key
        $database = (new \Kreait\Firebase\Factory())
            ->withServiceAccount(Yii::getAlias('@webroot') .'/../.firebase/'. $firebaseModel['serviceAccount'])
            ->withDatabaseUri($firebaseModel['path'])
            ->createDatabase()->getReference('practice/user1')
            ->set($data);

        // --- Modify specified data to Firebase "Realtime Database" ---
        $data = 'Hello 2';
        (new \Kreait\Firebase\Factory())
            ->withServiceAccount(Yii::getAlias('@webroot') .'/../.firebase/'. $firebaseModel['serviceAccount'])
            ->withDatabaseUri($firebaseModel['path'])
            ->createDatabase()->getReference('practice/user1')
            ->getChild('name') // modified key "name" from "Hello" to "Hello 2"
            ->set($data);

        // --- Remove specified data to Firebase "Realtime Database" ---
        (new \Kreait\Firebase\Factory())
            ->withServiceAccount(Yii::getAlias('@webroot') .'/../.firebase/'. $firebaseModel['serviceAccount'])
            ->withDatabaseUri($firebaseModel['path'])
            ->createDatabase()->getReference('practice/user1')
            ->getChild('name') // remove key "name"
            ->remove();
    }

    // ----------- !! Important !! For Firebase Guide/Example only, no function !!! --------------
    public function storageGuide()
    {
        // --- Get model from database ---
        $firebaseModel = Firebase::find()->where(['name' => 'MyProject-Storage'])->one();

        // --- Download file to you computer from firebase "Storage" ---
        (new \Kreait\Firebase\Factory())
        ->withServiceAccount(Yii::getAlias('@webroot') .'/../.firebase/'. $firebaseModel['serviceAccount'])
        ->createStorage()
        ->getBucket($firebaseModel['path'])
        ->object('abcdefg.png') // file name (need to know from firebase storage)
        ->downloadToFile('C:\to\path\abcdefg.png'); // insert the path where you want to download to

        // --- View image file from firebase "Storage" ---
        $database = (new \Kreait\Firebase\Factory())
            ->withServiceAccount(Yii::getAlias('@webroot') .'/../.firebase/'. $firebaseModel['serviceAccount'])
            ->createStorage()
            ->getBucket($firebaseModel['path'])
            ->object('practice/abcdefg.png') // file path and name
            ->signedUrl(new \DateTime('+1 hour')); // set image authorized time
        echo "<img src='$database' alt='Image'>";
        var_dump($database);

        // --- Get and View all folder and file name in firebase "Storage"
        $database = (new \Kreait\Firebase\Factory())
            ->withServiceAccount(Yii::getAlias('@webroot') .'/../.firebase/'. $firebaseModel['serviceAccount'])
            ->createStorage()
            ->getBucket($firebaseModel['path'])
            ->objects(['prefix' => 'practice']); // Specified folder path and name, if '', will display all folder and file
        foreach ($database as $oneDatabase) {
            $fileNames[] = $oneDatabase->name();
        }
        var_dump($fileNames);

        // --- Upload file to firebase "Storage" ---
        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, "imageFile");

            (new \Kreait\Firebase\Factory())
            ->withServiceAccount(Yii::getAlias('@webroot') .'/../.firebase/'. $firebaseModel['serviceAccount'])
            ->createStorage()
            ->getBucket($firebaseModel['path'])
            ->upload(
                fopen($image->tempName, 'r'), // your uploaded file path, "r" = read-only
                ['name' => 'practice/abcdefg.png'] // upload to firebase file path and name and extension, if folder name not exist, will auto create folder
            );
        }
    }
}
