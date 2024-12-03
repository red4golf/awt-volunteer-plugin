#!/bin/bash

# Install dependencies
npm install

# Create dist directory if it doesn't exist
mkdir -p dist

# Run webpack build
npm run build

# Output success message
echo "Build complete. Check dist/bundle.js"