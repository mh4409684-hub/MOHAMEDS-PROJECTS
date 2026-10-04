import os
import shutil

BASE_DIR = r"C:\Users\MOHAMEDY\.gemini\antigravity\scratch\SafarisRide"

def export_android_studio_project(app_id, app_name, package_name, html_file):
    project_dir = os.path.join(BASE_DIR, f"Android_Project_{app_name.replace(' ', '_')}")
    app_dir = os.path.join(project_dir, 'app')
    main_dir = os.path.join(app_dir, 'src', 'main')
    java_dir = os.path.join(main_dir, 'java', 'com', 'mohamedtech', app_id)
    res_dir = os.path.join(main_dir, 'res')
    assets_dir = os.path.join(main_dir, 'assets')

    os.makedirs(java_dir, exist_ok=True)
    os.makedirs(os.path.join(res_dir, 'values'), exist_ok=True)
    os.makedirs(os.path.join(res_dir, 'drawable'), exist_ok=True)
    os.makedirs(assets_dir, exist_ok=True)

    # 1. root build.gradle
    with open(os.path.join(project_dir, 'build.gradle'), 'w', encoding='utf-8') as f:
        f.write('''buildscript {
    repositories {
        google()
        mavenCentral()
    }
    dependencies {
        classpath 'com.android.tools.build:gradle:8.2.2'
    }
}
allprojects {
    repositories {
        google()
        mavenCentral()
    }
}''')

    # 2. settings.gradle
    with open(os.path.join(project_dir, 'settings.gradle'), 'w', encoding='utf-8') as f:
        f.write(f"include ':app'\nrootProject.name = \"{app_name}\"")

    # 3. app/build.gradle
    with open(os.path.join(app_dir, 'build.gradle'), 'w', encoding='utf-8') as f:
        f.write(f'''plugins {{
    id 'com.android.application'
}}

android {{
    namespace '{package_name}'
    compileSdk 34

    defaultConfig {{
        applicationId "{package_name}"
        minSdk 21
        targetSdk 34
        versionCode 1
        versionName "1.0.0"
    }}

    buildTypes {{
        release {{
            minifyEnabled false
            proguardFiles getDefaultProguardFile('proguard-android-optimize.txt'), 'proguard-rules.pro'
        }}
    }}
}}

dependencies {{
    implementation 'androidx.appcompat:appcompat:1.6.1'
    implementation 'com.google.android.material:material:1.11.0'
}}''')

    # 4. Manifest
    with open(os.path.join(main_dir, 'AndroidManifest.xml'), 'w', encoding='utf-8') as f:
        f.write(f'''<?xml version="1.0" encoding="utf-8"?>
<manifest xmlns:android="http://schemas.android.com/apk/res/android">
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
    <uses-permission android:name="android.permission.ACCESS_FINE_LOCATION" />
    <uses-permission android:name="android.permission.ACCESS_COARSE_LOCATION" />
    <uses-permission android:name="android.permission.WAKE_LOCK" />

    <application
        android:allowBackup="true"
        android:icon="@drawable/ic_launcher"
        android:label="{app_name}"
        android:roundIcon="@drawable/ic_launcher"
        android:supportsRtl="true"
        android:theme="@android:style/Theme.NoTitleBar"
        android:usesCleartextTraffic="true">
        <activity
            android:name=".MainActivity"
            android:configChanges="orientation|screenSize|keyboardHidden"
            android:exported="true">
            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>
        </activity>
    </application>
</manifest>''')

    # Copy files
    shutil.copy2(os.path.join(BASE_DIR, 'public', 'logo.png'), os.path.join(res_dir, 'drawable', 'ic_launcher.png'))
    for item in [html_file, 'index.html', 'manifest.json', 'sw.js']:
        src = os.path.join(BASE_DIR, item)
        if os.path.exists(src):
            shutil.copy2(src, os.path.join(assets_dir, item))
    os.makedirs(os.path.join(assets_dir, 'public'), exist_ok=True)
    shutil.copy2(os.path.join(BASE_DIR, 'public', 'logo.png'), os.path.join(assets_dir, 'public', 'logo.png'))

    # Copy Java
    src_java = os.path.join(BASE_DIR, 'android_builder', app_id, 'src', 'com', 'mohamedtech', app_id, 'MainActivity.java')
    if os.path.exists(src_java):
        shutil.copy2(src_java, os.path.join(java_dir, 'MainActivity.java'))

    print(f"Exported Android Studio project for {app_name} -> {project_dir}")

if __name__ == '__main__':
    export_android_studio_project('rider', 'Safaris Ride', 'com.mohamedtech.safarisride', 'rider.html')
    export_android_studio_project('driver', 'Safaris Drivers', 'com.mohamedtech.safarisdrivers', 'driver.html')
