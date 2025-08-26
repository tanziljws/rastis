<?php

// Test script untuk file upload API
// Jalankan dengan: php test_file_upload.php

$baseUrl = 'http://localhost:8000/api';

echo "=== Test File Upload API ===\n\n";

// Test 1: Login untuk dapat token
echo "1. Testing Login...\n";
$loginData = [
    'username' => 'admin',
    'password' => 'admin123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Parse response to get token
$responseData = json_decode($response, true);
$token = $responseData['token'] ?? null;

if ($token) {
    echo "Token received: " . substr($token, 0, 20) . "...\n\n";
    
    // Test 2: Get galeries untuk dapat galery_id
    echo "2. Getting galeries for galery_id...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/galeries?per_page=1');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "GET /galeries - HTTP Code: $httpCode\n";
    echo "Response: " . substr($response, 0, 200) . "...\n\n";
    
    // Parse galeries response to get galery_id
    $galeriesResponse = json_decode($response, true);
    $galeryId = $galeriesResponse['data'][0]['id'] ?? null;
    
    if ($galeryId) {
        echo "Using galery_id: $galeryId\n\n";
        
        // Test 3: Upload file dengan multipart form data
        echo "3. Testing File Upload...\n";
        
        // Create a test image file
        $testImagePath = 'test_image.jpg';
        $testImageContent = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxAAPwA/8A');
        file_put_contents($testImagePath, $testImageContent);
        
        // Prepare multipart form data
        $boundary = uniqid();
        $delimiter = "\r\n";
        $postData = '';
        
        // Add galery_id
        $postData .= '--' . $boundary . $delimiter;
        $postData .= 'Content-Disposition: form-data; name="galery_id"' . $delimiter . $delimiter;
        $postData .= $galeryId . $delimiter;
        
        // Add judul
        $postData .= '--' . $boundary . $delimiter;
        $postData .= 'Content-Disposition: form-data; name="judul"' . $delimiter . $delimiter;
        $postData .= 'Test Foto Upload ' . date('Y-m-d H:i:s') . $delimiter;
        
        // Add file
        $postData .= '--' . $boundary . $delimiter;
        $postData .= 'Content-Disposition: form-data; name="file"; filename="test_image.jpg"' . $delimiter;
        $postData .= 'Content-Type: image/jpeg' . $delimiter . $delimiter;
        $postData .= $testImageContent . $delimiter;
        
        $postData .= '--' . $boundary . '--' . $delimiter;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/fotos');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: multipart/form-data; boundary=' . $boundary,
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "POST /fotos (file upload) - HTTP Code: $httpCode\n";
        echo "Response: $response\n\n";
        
        // Parse foto response to get foto_id
        $fotoResponse = json_decode($response, true);
        $fotoId = $fotoResponse['data']['id'] ?? null;
        
        if ($fotoId) {
            echo "Created foto with ID: $fotoId\n\n";
            
            // Test 4: Get the uploaded foto
            echo "4. Getting uploaded foto...\n";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/fotos/' . $fotoId);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            echo "GET /fotos/$fotoId - HTTP Code: $httpCode\n";
            echo "Response: " . substr($response, 0, 300) . "...\n\n";
            
            // Test 5: Delete the foto (this will also delete the file)
            echo "5. Deleting foto (will also delete file)...\n";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/fotos/' . $fotoId);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            echo "DELETE /fotos/$fotoId - HTTP Code: $httpCode\n";
            echo "Response: $response\n\n";
        }
        
        // Clean up test file
        if (file_exists($testImagePath)) {
            unlink($testImagePath);
        }
    } else {
        echo "No galeries found. Please create a galery first.\n";
    }
    
    // Test 6: Logout
    echo "6. Testing Logout...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/logout');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "POST /logout - HTTP Code: $httpCode\n";
    echo "Response: $response\n\n";
    
} else {
    echo "Failed to get token from login response\n";
}

echo "=== Test Complete ===\n";
