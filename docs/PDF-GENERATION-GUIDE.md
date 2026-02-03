# PDF Generation Guide

How to convert documentation to professional PDF format.

---

## Method 1: Using Pandoc (Recommended)

### Install Pandoc

```bash
# Ubuntu/Debian
sudo apt-get update
sudo apt-get install pandoc texlive-latex-base texlive-fonts-recommended texlive-latex-extra

# macOS
brew install pandoc basictex

# Or download from: https://pandoc.org/installing.html
```

### Convert Single Document

```bash
cd docs

# Basic conversion
pandoc ARCHITECTURE.md -o ARCHITECTURE.pdf

# With table of contents
pandoc ARCHITECTURE.md -o ARCHITECTURE.pdf --toc --toc-depth=3

# Professional formatting
pandoc ARCHITECTURE.md -o ARCHITECTURE.pdf \
  --toc \
  --toc-depth=3 \
  --number-sections \
  --highlight-style=tango \
  -V geometry:margin=1in \
  -V linkcolor:blue \
  -V fontsize=11pt \
  --pdf-engine=xelatex
```

### Convert All Documentation

```bash
#!/bin/bash
# convert-all-to-pdf.sh

cd docs

# Client documentation
pandoc QUICK-START.md -o QUICK-START.pdf --toc -V geometry:margin=1in
pandoc CLIENT-GUIDE.md -o CLIENT-GUIDE.pdf --toc --toc-depth=3 -V geometry:margin=1in

# Technical documentation
pandoc ARCHITECTURE.md -o ARCHITECTURE.pdf --toc --toc-depth=3 -V geometry:margin=1in
pandoc TERRAFORM-GUIDE.md -o TERRAFORM-GUIDE.pdf --toc --toc-depth=3 -V geometry:margin=1in

cd technical
pandoc INFRASTRUCTURE.md -o INFRASTRUCTURE.pdf --toc -V geometry:margin=1in
pandoc OPERATIONS.md -o OPERATIONS.pdf --toc -V geometry:margin=1in
pandoc SECURITY.md -o SECURITY.pdf --toc -V geometry:margin=1in

echo "✅ All PDFs generated!"
```

---

## Method 2: Using Markdown to PDF Extension (VS Code)

### Install Extension

1. Open VS Code
2. Go to Extensions (Ctrl+Shift+X)
3. Search for "Markdown PDF"
4. Install "yzane.markdown-pdf"

### Convert Document

1. Open any `.md` file
2. Right-click in editor
3. Select "Markdown PDF: Export (pdf)"
4. PDF saved in same directory

### Configure Settings

```json
// settings.json
{
  "markdown-pdf.breaks": true,
  "markdown-pdf.headerTemplate": "<div style=\"font-size: 9px; margin-left: 1cm;\"> <span class='title'></span></div>",
  "markdown-pdf.footerTemplate": "<div style=\"font-size: 9px; margin: 0 auto;\"> <span class='pageNumber'></span> / <span class='totalPages'></span></div>",
  "markdown-pdf.displayHeaderFooter": true,
  "markdown-pdf.margin.top": "1.5cm",
  "markdown-pdf.margin.bottom": "1.5cm",
  "markdown-pdf.margin.left": "1cm",
  "markdown-pdf.margin.right": "1cm"
}
```

---

## Method 3: Using Chrome/Chromium

### Steps

1. Open markdown file in Chrome (use a markdown viewer extension)
2. Press Ctrl+P (Cmd+P on Mac)
3. Select "Save as PDF"
4. Configure:
   - Layout: Portrait
   - Margins: Default
   - Background graphics: Yes
5. Click "Save"

### Markdown Viewer Extension

Install "Markdown Viewer" extension for Chrome:
- https://chrome.google.com/webstore/detail/markdown-viewer

---

## Method 4: Online Conversion

### Websites

1. **Markdown to PDF**: https://md2pdf.netlify.app/
   - Upload .md file
   - Download PDF

2. **Dillinger**: https://dillinger.io/
   - Paste markdown
   - Export as PDF

3. **Pandoc Online**: https://pandoc.org/try/
   - Paste markdown
   - Select PDF output
   - Download

---

## Batch Conversion Script

Create `generate-pdfs.sh`:

```bash
#!/bin/bash

# Generate all documentation PDFs
# Usage: ./generate-pdfs.sh

set -e

DOCS_DIR="docs"
OUTPUT_DIR="docs/pdf"

# Create output directory
mkdir -p "$OUTPUT_DIR"

echo "🔄 Generating PDFs..."

# Function to convert markdown to PDF
convert_to_pdf() {
    local input=$1
    local output=$2
    local title=$3

    echo "  📄 Converting $input..."

    pandoc "$input" -o "$output" \
        --toc \
        --toc-depth=3 \
        --number-sections \
        --highlight-style=tango \
        -V geometry:margin=1in \
        -V linkcolor:blue \
        -V urlcolor:blue \
        -V fontsize=11pt \
        -V title="$title" \
        -V author="Naeem Dosh - TaxPlanner.app" \
        -V date="$(date '+%B %d, %Y')" \
        --pdf-engine=xelatex
}

# Client Documentation
convert_to_pdf "$DOCS_DIR/QUICK-START.md" "$OUTPUT_DIR/Quick-Start-Guide.pdf" "Quick Start Guide"
convert_to_pdf "$DOCS_DIR/CLIENT-GUIDE.md" "$OUTPUT_DIR/Client-Guide.pdf" "Complete Client Guide"

# Technical Documentation
convert_to_pdf "$DOCS_DIR/ARCHITECTURE.md" "$OUTPUT_DIR/Architecture-Documentation.pdf" "Architecture Documentation"
convert_to_pdf "$DOCS_DIR/TERRAFORM-GUIDE.md" "$OUTPUT_DIR/Terraform-Guide.pdf" "Terraform Infrastructure Guide"

# Technical Details
convert_to_pdf "$DOCS_DIR/technical/INFRASTRUCTURE.md" "$OUTPUT_DIR/Infrastructure-Details.pdf" "Infrastructure Details"
convert_to_pdf "$DOCS_DIR/technical/OPERATIONS.md" "$OUTPUT_DIR/Operations-Manual.pdf" "Operations Manual"
convert_to_pdf "$DOCS_DIR/technical/SECURITY.md" "$OUTPUT_DIR/Security-Guide.pdf" "Security Guide"

# Main README
convert_to_pdf "README.md" "$OUTPUT_DIR/Project-Overview.pdf" "TaxPlanner.app - Project Overview"

echo "✅ All PDFs generated in $OUTPUT_DIR"
echo ""
echo "📑 Generated files:"
ls -lh "$OUTPUT_DIR"/*.pdf
```

Make it executable:
```bash
chmod +x generate-pdfs.sh
./generate-pdfs.sh
```

---

## Pandoc Template (Custom Styling)

Create `template.latex`:

```latex
\documentclass[11pt,a4paper]{article}

% Packages
\usepackage{geometry}
\usepackage{hyperref}
\usepackage{graphicx}
\usepackage{fancyhdr}
\usepackage{listings}
\usepackage{xcolor}

% Geometry
\geometry{margin=1in}

% Header/Footer
\pagestyle{fancy}
\fancyhf{}
\rhead{TaxPlanner.app - HIPAA POC}
\lhead{$title$}
\cfoot{\thepage}

% Hyperlinks
\hypersetup{
    colorlinks=true,
    linkcolor=blue,
    filecolor=magenta,
    urlcolor=cyan,
}

% Code blocks
\definecolor{codegray}{rgb}{0.95,0.95,0.95}
\lstset{
    backgroundcolor=\color{codegray},
    basicstyle=\ttfamily\small,
    breaklines=true,
    frame=single
}

% Title
\title{$title$}
\author{$author$}
\date{$date$}

\begin{document}

\maketitle
\newpage

$if(toc)$
\tableofcontents
\newpage
$endif$

$body$

\end{document}
```

Use template:
```bash
pandoc ARCHITECTURE.md -o ARCHITECTURE.pdf \
  --template=template.latex \
  -V title="Architecture Documentation" \
  -V author="Naeem Dosh" \
  -V date="February 3, 2026"
```

---

## Recommended Settings

### For Client Documents (Simple)

```bash
pandoc CLIENT-GUIDE.md -o CLIENT-GUIDE.pdf \
  --toc \
  -V geometry:margin=1.25in \
  -V fontsize=12pt \
  -V colorlinks
```

### For Technical Documents (Detailed)

```bash
pandoc ARCHITECTURE.md -o ARCHITECTURE.pdf \
  --toc \
  --toc-depth=4 \
  --number-sections \
  -V geometry:margin=1in \
  -V fontsize=11pt \
  -V colorlinks \
  --highlight-style=tango \
  --pdf-engine=xelatex
```

---

## Troubleshooting

### Issue: "pandoc: xelatex not found"

**Solution**:
```bash
# Install LaTeX
sudo apt-get install texlive-xetex

# Or use different engine
pandoc file.md -o file.pdf --pdf-engine=pdflatex
```

### Issue: Unicode characters not displaying

**Solution**:
```bash
# Use XeLaTeX engine
pandoc file.md -o file.pdf --pdf-engine=xelatex
```

### Issue: Images not showing

**Solution**:
```bash
# Ensure image paths are correct (relative to markdown file)
# Use absolute paths if needed
pandoc file.md -o file.pdf --resource-path=.:./images
```

### Issue: Tables cut off

**Solution**:
```bash
# Reduce margins
pandoc file.md -o file.pdf -V geometry:margin=0.75in

# Or use landscape
pandoc file.md -o file.pdf -V classoption=landscape
```

---

## Professional PDF Package

Create complete documentation package:

```bash
#!/bin/bash
# create-pdf-package.sh

VERSION="1.0"
DATE=$(date +%Y%m%d)
PACKAGE_NAME="TaxPlanner-Documentation-${VERSION}-${DATE}"

echo "Creating documentation package: $PACKAGE_NAME"

# Create directory structure
mkdir -p "$PACKAGE_NAME/client"
mkdir -p "$PACKAGE_NAME/technical"

# Generate PDFs
./generate-pdfs.sh

# Copy PDFs
cp docs/pdf/Quick-Start-Guide.pdf "$PACKAGE_NAME/client/"
cp docs/pdf/Client-Guide.pdf "$PACKAGE_NAME/client/"
cp docs/pdf/Architecture-Documentation.pdf "$PACKAGE_NAME/technical/"
cp docs/pdf/Terraform-Guide.pdf "$PACKAGE_NAME/technical/"
cp docs/pdf/Infrastructure-Details.pdf "$PACKAGE_NAME/technical/"
cp docs/pdf/Operations-Manual.pdf "$PACKAGE_NAME/technical/"
cp docs/pdf/Security-Guide.pdf "$PACKAGE_NAME/technical/"
cp docs/pdf/Project-Overview.pdf "$PACKAGE_NAME/"

# Create README
cat > "$PACKAGE_NAME/README.txt" << EOF
TaxPlanner.app - Documentation Package
Version: $VERSION
Generated: $(date)
Developer: Naeem Dosh

CONTENTS:
=========

client/
  - Quick-Start-Guide.pdf - 5-minute setup guide
  - Client-Guide.pdf - Complete user manual

technical/
  - Architecture-Documentation.pdf - System architecture
  - Terraform-Guide.pdf - Infrastructure as code
  - Infrastructure-Details.pdf - AWS resources
  - Operations-Manual.pdf - Day-to-day operations
  - Security-Guide.pdf - Security controls

Project-Overview.pdf - Overall project summary

APPLICATION:
============
URL: https://taxplanner.app
Region: us-east-2 (Ohio)
Status: Production

SUPPORT:
========
Developer: Naeem Dosh
Platform: Fiverr
Date: February 2026
EOF

# Create ZIP
zip -r "${PACKAGE_NAME}.zip" "$PACKAGE_NAME"

echo "✅ Package created: ${PACKAGE_NAME}.zip"
ls -lh "${PACKAGE_NAME}.zip"
```

---

## File Naming Convention

```
Quick-Start-Guide.pdf
Client-Guide.pdf
Architecture-Documentation.pdf
Terraform-Guide.pdf
Infrastructure-Details.pdf
Operations-Manual.pdf
Security-Guide.pdf
Project-Overview.pdf
```

Use:
- Uppercase for important words
- Hyphens between words
- Descriptive names
- Consistent formatting

---

## Quality Checklist

Before generating final PDFs:

- [ ] All markdown files formatted correctly
- [ ] All links working (internal and external)
- [ ] All code blocks have syntax highlighting
- [ ] Tables formatted properly
- [ ] Images embedded correctly
- [ ] Table of contents accurate
- [ ] Page numbers added
- [ ] Headers/footers configured
- [ ] Margins appropriate
- [ ] Font size readable (11-12pt)
- [ ] Spell-checked
- [ ] Version number included
- [ ] Date included
- [ ] Author/developer name included

---

## Quick Reference

### Convert one file:
```bash
pandoc FILE.md -o FILE.pdf --toc
```

### Convert with custom options:
```bash
pandoc FILE.md -o FILE.pdf \
  --toc \
  --toc-depth=3 \
  --number-sections \
  -V geometry:margin=1in \
  -V fontsize=11pt
```

### Batch convert:
```bash
for f in *.md; do
  pandoc "$f" -o "${f%.md}.pdf" --toc
done
```

---

**Developer**: Naeem Dosh
**Date**: February 3, 2026
**Project**: TaxPlanner.app

**End of PDF Generation Guide**
