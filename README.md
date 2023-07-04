# User Guide: Using Firebase "Storage" with Yii2 && PHP method
This repository provides a step by step guide on integrating Firebase storage with Yii2 and PHP, allowing you to leverage Firebase storage services in your Yii2 applications. 

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

### Step 3: Usage
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
  - ** How to get it. **
    1. Go to the Firebase console website https://console.firebase.google.com
    2. Select your Firebase project from the project list (or create a new project if you haven't already).
    3. Click on the gear icon (settings) near the top left corner of the page and select "Project settings".
    4. In the project settings page, navigate to the "Service Accounts" tab.
    5. Under the "Firebase Admin SDK" section, click on the "Generate new private key" button.
- $bucketPath
  - ** How to get it. **
    1. Go to the Firebase Console website
    2. Select your Firebase project from the project list (or create a new project if you haven't already).
    3. In the left sidebar, click on "Storage" to access Firebase Storage.
    4. You will see the default bucket listed under the "Files" tab. It will be in the format gs://<your-project-id>.appspot.com/. Copy this bucket URL.
    5. To paste in ->getBucket(), need to remove "gs://".
    6. Use the copied URL as the $path value in your code. Replace '<your-project-id>.appspot.com/'.


