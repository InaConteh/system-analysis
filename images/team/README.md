# Team Photos Guide

## How to Add Team Member Photos

This folder contains photos for the team members displayed on the About page.

### Required Photos

You need to add the following three photos:

1. **founder.jpg** - Photo of John Amadu (Founder & CEO)
2. **head-player-relations.jpg** - Photo of Sarah Williams (Head of Player Relations)
3. **contract-specialist.jpg** - Photo of Michael Chen (Contract Negotiation Specialist)

### Photo Specifications

- **Format**: JPG, JPEG, or PNG
- **Recommended Size**: 500x500 pixels or larger (square)
- **Aspect Ratio**: 1:1 (square) works best
- **File Size**: Keep under 500KB for optimal loading

### How to Add Photos

1. Prepare your photos (crop them to square if needed)
2. Rename them to match the filenames above
3. Copy them to this folder: `images/team/`
4. Refresh the About page to see your photos

### Fallback Image

If a photo is missing, the page will automatically display `default-avatar.png` as a placeholder.

### Example File Structure

```
images/
  team/
    ├── founder.jpg                      ← Add this
    ├── head-player-relations.jpg        ← Add this
    ├── contract-specialist.jpg          ← Add this
    └── default-avatar.png               ✓ Already included
```

### Tips

- Use professional headshots for best results
- Ensure good lighting and clear faces
- Keep backgrounds simple or use a solid color
- Photos will be displayed in a circle, so center the face

### Updating Team Members

If you want to change the names or roles, edit the `about.php` file:

- Lines 88-96: Founder & CEO
- Lines 97-105: Head of Player Relations
- Lines 106-114: Contract Negotiation Specialist

---

**Need Help?** The photos are displayed with a blue border and will scale slightly on hover for a professional effect.
