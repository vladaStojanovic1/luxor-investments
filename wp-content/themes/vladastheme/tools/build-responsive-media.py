from pathlib import Path
import json,hashlib,subprocess
import argparse
parser=argparse.ArgumentParser(description='Build responsive WebP copies without changing media originals.')
parser.add_argument('--inventory',required=True,type=Path)
args=parser.parse_args()
root=Path(__file__).resolve().parents[4];out=Path(__file__).resolve().parents[1]/'src/images/optimized-media';out.mkdir(exist_ok=True)
manifest={};original=0;total=0
for line in args.inventory.read_text().splitlines():
 d=json.loads(line);src=(root/'wp-content/uploads'/d['file']).resolve();src.relative_to((root/'wp-content/uploads').resolve());digest=hashlib.sha256(src.read_bytes()).hexdigest()[:12];variants=[]
 widths=sorted(set([w for w in [160,320,640,960,1440,1920] if w<d['width']]+[min(d['width'],1920)]))
 for width in widths:
  name=f'luxor-{digest}-{width}.webp';dest=out/name
  # Resize/compress only; keep the original artwork, transparency and color intent.
  subprocess.run(['convert',str(src),'-auto-orient','-resize',f'{width}x>','-strip','-quality','87','-define','webp:method=6',str(dest)],check=True)
  dims=subprocess.check_output(['identify','-format','%w %h',str(dest)],text=True).split()
  variants.append({'file':name,'width':int(dims[0]),'height':int(dims[1]),'bytes':dest.stat().st_size});total+=dest.stat().st_size
 manifest[d['file']]={'original_bytes':d['bytes'],'width':d['width'],'height':d['height'],'variants':variants};original+=d['bytes']
(out/'manifest.json').write_text(json.dumps(manifest,ensure_ascii=False,indent=2)+'\n')
print('Sources:',len(manifest),'original bytes:',original,'all responsive variants bytes:',total)
