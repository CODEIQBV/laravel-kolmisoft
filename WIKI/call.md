## **Call Class**

The `Call` class is used to retrieve call data from the MOR API for a specific user or reseller within a specified time period.

### **Namespace**
```php
use CODEIQBV\Kolmisoft\Api\Call;
```

### **Methods**

#### 1. **getUserCalls()**

**Description**:
- Fetches call details based on the provided parameters.

**Parameters**:
- `$params` (array): Query parameters. Key options:
    - `s_user` (string): User ID in MOR database (required if `s_reseller` is not provided).
    - `s_reseller` (string): Reseller ID in MOR database (required if `s_user` is not provided).
    - `period_start` (int): Unix timestamp for the start of the period (default: today at 00:00).
    - `period_end` (int): Unix timestamp for the end of the period (default: today at 23:59).
    - `s_call_type` (string): Call type (default: `all`). Possible values: `all`, `answered`, `no answer`, `failed`, `busy`.
    - Other optional parameters: See MOR API documentation.

**Return**:
- Array of call statistics or raw response (if `$raw = true`).

**Usage**:
```php
$callApi = new \CODEIQBV\Kolmisoft\Api\Call();

// Fetch calls for a specific user
$params = [
    's_user' => '12345', // User ID
    'period_start' => strtotime('2024-12-01 00:00'), // Optional
    'period_end' => strtotime('2024-12-01 23:59'),   // Optional
];

$response = $callApi->getUserCalls($params);

print_r($response);
```

#### 2. **Error Handling**
- Common errors include:
    - `Incorrect hash`: Ensure the parameters match the hash string requirements.
    - `User was not found`: Verify the `s_user` or `s_reseller` value.
    - `Access Denied`: Check user permissions in MOR.

---

## **ActiveCall Class**

The `ActiveCall` class retrieves the active call details for a specific user, reseller, or device.

### **Namespace**
```php
use CODEIQBV\Kolmisoft\Api\ActiveCall;
```

### **Methods**

#### 1. **getActiveCalls()**

**Description**:
- Retrieves active call details based on the provided parameters.

**Parameters**:
- `$params` (array): Query parameters. Key options:
    - `u` (string): Username for authentication (automatically added from config).
    - `s_status` (string): Call status. Possible values: `Ringing`, `Answered`.
    - `s_server` (string): Server ID (optional).
    - Other optional parameters: See MOR API documentation.

**Return**:
- Array of active call details or raw response (if `$raw = true`).

**Usage**:
```php
$activeCallApi = new \CODEIQBV\Kolmisoft\Api\ActiveCall();

// Fetch active calls with specific status
$params = [
    's_status' => 'Ringing', // Call status
    's_server' => '2',       // Optional server ID
];

$response = $activeCallApi->getActiveCalls($params);

print_r($response);
```

#### 2. **Error Handling**
- Common errors include:
    - `Server was not found`: Verify the `s_server` parameter.
    - `Status value must be Ringing or Answered`: Ensure `s_status` is valid.

---

## **Common Steps for Both Classes**

1. **Initialization**:
    - Include the namespace for the class you want to use.
    - Create an instance of the class.

2. **Pass Parameters**:
    - Ensure all required parameters are passed in the `$params` array.
    - Include optional parameters as needed.

3. **Error Debugging**:
    - Use `try-catch` to handle exceptions.
    - Debug by logging or printing the response to verify the output.

4. **Raw Responses**:
    - Set `$raw = true` in the method call to get the raw API response.

---

## **Example: Unified Workflow**

```php
use CODEIQBV\Kolmisoft\Api\Call;
use CODEIQBV\Kolmisoft\Api\ActiveCall;

try {
    // Initialize Call API
    $callApi = new Call();
    $callParams = [
        's_user' => '12345', 
        'period_start' => strtotime('2024-12-01 00:00'), 
        'period_end' => strtotime('2024-12-01 23:59')
    ];
    $callResponse = $callApi->getUserCalls($callParams);
    print_r($callResponse);

    // Initialize ActiveCall API
    $activeCallApi = new ActiveCall();
    $activeCallParams = [
        's_status' => 'Ringing',
        's_server' => '2'
    ];
    $activeCallResponse = $activeCallApi->getActiveCalls($activeCallParams);
    print_r($activeCallResponse);

} catch (\CODEIQBV\Kolmisoft\Exceptions\ApiException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

---

This documentation should help you use and extend the `Call` and `ActiveCall` classes effectively.
