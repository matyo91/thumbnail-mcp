# Thumbnail MCP Tool

A Replicate-based tool for generating thumbnail images via the MCP protocol.

## Setup

1. Make sure you have a Replicate API key
2. Set the environment variable `REPLICATE_API_KEY` with your key
3. Install dependencies:
   ```
   composer install
   ```

## Testing the Tool

### Method 1: Using the Mock Test

This method uses a mocked platform to test the tool structure without making real API calls:

```bash
php test_thumbnail.php
```

### Method 2: Using the Real API

This method makes actual API calls to Replicate:

```bash
REPLICATE_API_KEY=your_api_key php test_real.php
```

### Method 3: Using the Symfony Command

This is the most realistic test as it uses the Symfony DI container:

```bash
# Make sure you have the API key in .env.local
bin/console app:test-thumbnail "Your Name"
```

## Tool Usage in MCP

The tool is registered via the `AsTool` attribute and will be available in the MCP toolbox with the name `mcp_thumbnail`. It takes a single string parameter `name` that will be used in the prompt.

Example usage:

```json
{
  "name": "John Doe"
}
```

The tool will return a string with the URL of the generated thumbnail image. 