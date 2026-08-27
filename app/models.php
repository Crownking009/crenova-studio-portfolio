<?php
declare(strict_types=1);

abstract class Model {
    protected static string $table;
    public static function all(string $order = 'created_at DESC'): array {
        $pdo = db(); if (!$pdo) return static::seed();
        try { return $pdo->query('SELECT * FROM `' . static::$table . '` ORDER BY ' . $order)->fetchAll(); } catch (Throwable) { return static::seed(); }
    }
    public static function seed(): array { return []; }
}
class User {
    public static function attempt(string $username, string $password): ?array {
        $pdo = db(); if (!$pdo) return ($username === 'Oba' && $password === 'Jesusislord666') ? ['id' => 1, 'name' => 'Oba'] : null;
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1'); $stmt->execute([$username]); $user = $stmt->fetch();
        return ($user && password_verify($password, $user['password'])) ? $user : null;
    }
}
class Project extends Model {
    protected static string $table = 'projects';
    public static function seed(): array { return [
        ['id'=>1,'title'=>'Amina Atelier','slug'=>'amina-atelier','category'=>'BRANDING','client'=>'Amina Atelier','description'=>'A tactile identity for a Lagos fashion house where every detail feels considered.','image'=>'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=1400&q=82','featured'=>1,'tools'=>'Strategy, Identity, Art Direction','results'=>'A memorable, flexible brand system ready for retail.'],
        ['id'=>2,'title'=>'Form & Function','slug'=>'form-and-function','category'=>'WEBSITE','client'=>'F&F Studio','description'=>'A direct-to-client web presence that turns curiosity into appointment requests.','image'=>'https://images.unsplash.com/photo-1558655146-9f40138edfeb?auto=format&fit=crop&w=1400&q=82','featured'=>1,'tools'=>'UX, UI, Development','results'=>'A clear path from discovery to conversion.'],
        ['id'=>3,'title'=>'Oro Coffee','slug'=>'oro-coffee','category'=>'GRAPHICS DESIGN','client'=>'Oro Coffee Co.','description'=>'A sun-warmed visual campaign for a coffee company with roots in connection.','image'=>'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1400&q=82','featured'=>1,'tools'=>'Campaign, Photography, Social','results'=>'A launch toolkit built to travel across channels.'],
        ['id'=>4,'title'=>'The Sunday Edit','slug'=>'the-sunday-edit','category'=>'PRINTING','client'=>'Sunday Edit','description'=>'A quiet, luxurious editorial direction for a design-minded publication.','image'=>'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1400&q=82','featured'=>0,'tools'=>'Editorial, Photography','results'=>'A visual language with lasting character.'],
        ['id'=>5,'title'=>'Noma Living','slug'=>'noma-living','category'=>'MOBILE APP','client'=>'Noma Living','description'=>'A calm mobile companion that makes home styling feel beautifully simple.','image'=>'https://images.unsplash.com/photo-1512428559087-560fa5ceab42?auto=format&fit=crop&w=1400&q=82','featured'=>1,'tools'=>'UX, UI, Prototyping','results'=>'A product experience with an effortless flow.'],
        ['id'=>6,'title'=>'Pavilion OS','slug'=>'pavilion-os','category'=>'SOFTWARE','client'=>'Pavilion','description'=>'A practical software platform designed to bring complex operations into focus.','image'=>'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1400&q=82','featured'=>0,'tools'=>'Product Strategy, UX, Interface','results'=>'A clearer operational system for growing teams.'],
        ['id'=>7,'title'=>'Koru Objects','slug'=>'koru-objects','category'=>'PRODUCTS DESIGN','client'=>'Koru Objects','description'=>'An object collection made tactile, functional and instantly recognisable.','image'=>'https://images.unsplash.com/photo-1610701596007-11502861dcfa?auto=format&fit=crop&w=1400&q=82','featured'=>0,'tools'=>'Product Direction, Packaging','results'=>'A retail-ready collection with a distinct presence.'],
        ['id'=>8,'title'=>'Open House','slug'=>'open-house','category'=>'OTHERS','client'=>'Open House','description'=>'A culture-led creative collaboration built around gathering and shared ideas.','image'=>'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=1400&q=82','featured'=>0,'tools'=>'Creative Direction, Event Design','results'=>'A memorable experience for the community.']
    ]; }
    public static function featured(): array { return array_values(array_filter(static::all(), fn($p) => !empty($p['featured']))); }
    public static function findBySlug(string $slug): ?array { foreach (static::all() as $item) if (($item['slug'] ?? '') === $slug) return $item; return null; }
    public static function gallery(int $projectId): array {
        $pdo = db();
        if ($pdo) {
            try { $stmt = $pdo->prepare('SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order, id'); $stmt->execute([$projectId]); return $stmt->fetchAll(); } catch (Throwable) { return []; }
        }
        $fallback = [
            1 => ['https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1200&q=82','https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1200&q=82'],
            2 => ['https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=82','https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=1200&q=82'],
            3 => ['https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=1200&q=82','https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=1200&q=82']
        ];
        return array_map(fn(string $path) => ['path' => $path, 'alt_text' => 'Crenova project gallery image'], $fallback[$projectId] ?? []);
    }
    public static function deleteImage(int $imageId, int $projectId): void {
        $pdo = db(); if (!$pdo || $imageId < 1 || $projectId < 1) return;
        $stmt = $pdo->prepare('SELECT path FROM project_images WHERE id = ? AND project_id = ?'); $stmt->execute([$imageId, $projectId]); $image = $stmt->fetch();
        if (!$image) return;
        $delete = $pdo->prepare('DELETE FROM project_images WHERE id = ? AND project_id = ?'); $delete->execute([$imageId, $projectId]);
        $file = __DIR__ . '/../' . ltrim((string) $image['path'], '/'); if (is_file($file) && str_starts_with(realpath($file) ?: '', realpath(__DIR__ . '/../uploads') ?: '')) unlink($file);
    }
}
class Service extends Model { protected static string $table = 'services'; public static function seed(): array { return [
    ['id'=>1,'title'=>'Branding','icon'=>'✦','description'=>'Identities that stay recognisable long after the first impression.'], ['id'=>2,'title'=>'Digital Experiences','icon'=>'◌','description'=>'Websites and products with beauty, clarity, and a reason to return.'], ['id'=>3,'title'=>'Content & Campaigns','icon'=>'↗','description'=>'Visual stories made to earn attention and build momentum.'], ['id'=>4,'title'=>'Photo & Film','icon'=>'◐','description'=>'Images with texture, emotion, and a point of view.']
]; } }
class Product extends Model { protected static string $table = 'products'; public static function seed(): array { return [
    ['id'=>1,'name'=>'Crenova Brand Sprint','slug'=>'brand-sprint','price'=>'85000','category'=>'Strategy','description'=>'A focused half-day brand clarity session.','image'=>'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=900&q=80','availability'=>1],
    ['id'=>2,'name'=>'Social Launch Kit','slug'=>'social-launch-kit','price'=>'45000','category'=>'Design','description'=>'A polished editable social media launch collection.','image'=>'https://images.unsplash.com/photo-1545235617-9465d2a55698?auto=format&fit=crop&w=900&q=80','availability'=>1],
    ['id'=>3,'name'=>'Content Direction Call','slug'=>'content-direction-call','price'=>'30000','category'=>'Consulting','description'=>'A one-to-one creative direction session.','image'=>'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=900&q=80','availability'=>1]
]; } }
class Blog extends Model { protected static string $table = 'blogs'; public static function seed(): array { return [
    ['id'=>1,'title'=>'What makes a brand feel inevitable?','slug'=>'what-makes-a-brand-feel-inevitable','category'=>'Thinking','excerpt'=>'The small decisions that turn a visual identity into a feeling people trust.','content'=>'The strongest brands do not demand attention. They make their point with calm precision, then leave room for an audience to see itself in the story.'],
    ['id'=>2,'title'=>'A better brief starts with better questions','slug'=>'a-better-brief','category'=>'Process','excerpt'=>'How we create clarity before a pixel is designed or a frame is filmed.','content'=>'Before the moodboards and sketches, there is a conversation. Good creative work begins when everyone is honest about the problem worth solving.']
]; } public static function findBySlug(string $slug): ?array { foreach (static::all() as $item) if (($item['slug'] ?? '') === $slug) return $item; return null; } }
class Message { public static function create(array $data): void { $pdo = db(); if (!$pdo) return; $stmt=$pdo->prepare('INSERT INTO messages (name,email,phone,subject,message) VALUES (?,?,?,?,?)'); $stmt->execute([$data['name'],$data['email'],$data['phone'],$data['subject'],$data['message']]); } }
class Order { public static function create(array $data): void { $pdo = db(); if (!$pdo) return; $stmt=$pdo->prepare('INSERT INTO orders (customer_name,phone,address,items,total) VALUES (?,?,?,?,?)'); $stmt->execute([$data['customer_name'],$data['phone'],$data['address'],$data['items'],$data['total']]); } }
class Booking { public static function create(array $d): void { $pdo=db(); if (!$pdo)return; $check=$pdo->prepare("SELECT id FROM bookings WHERE booking_date=? AND booking_time=? AND status IN ('approved','pending')");$check->execute([$d['booking_date'],$d['booking_time']]);if($check->fetch())throw new RuntimeException('That time is unavailable.');$s=$pdo->prepare('INSERT INTO bookings (service,name,email,phone,booking_date,booking_time,notes) VALUES (?,?,?,?,?,?,?)');$s->execute(array_values($d)); } public static function updateStatus(int $id,string $status):void{$pdo=db();if($pdo){$s=$pdo->prepare('UPDATE bookings SET status=? WHERE id=?');$s->execute([$status,$id]);}} }
class AdminResource {
    public static function rows(string $table): array { $allowed=['projects','products','services','blogs','testimonials','clients','orders','bookings','messages']; if(!in_array($table,$allowed,true))return[]; $pdo=db(); if(!$pdo)return $table==='projects'?Project::all():($table==='products'?Product::all():[]); try{return $pdo->query('SELECT * FROM `'.$table.'` ORDER BY id DESC')->fetchAll();}catch(Throwable){return[];} }
    private static function uploadImage(array $file): ?string {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 5 * 1024 * 1024) return null;
        $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file((string) $file['tmp_name']);
        $types = ['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','webp'=>'image/webp'];
        if (!isset($types[$extension]) || $types[$extension] !== $mime) return null;
        $name = bin2hex(random_bytes(8)) . '.' . $extension;
        return move_uploaded_file($file['tmp_name'], __DIR__ . '/../uploads/' . $name) ? 'uploads/' . $name : null;
    }
    public static function save(string $table, array $input, array $files, int $id): void {
        $allowed=['projects'=>['title','slug','category','client','description','tools','results','featured'],'products'=>['name','slug','price','category','description','availability'],'services'=>['title','icon','description'],'blogs'=>['title','slug','category','excerpt','content','meta_title','meta_description'],'testimonials'=>['name','role','quote','rating'],'clients'=>['name','website'],'orders'=>['customer_name','phone','address','items','total','status']];
        if (!isset($allowed[$table])) return;
        $pdo = db(); if (!$pdo) return;
        $data=[]; foreach ($allowed[$table] as $field) $data[$field] = clean($input[$field] ?? '');
        if (in_array($table, ['projects','products'], true) && isset($files['image'])) { $mainImage = self::uploadImage($files['image']); if ($mainImage) $data['image'] = $mainImage; }
        $fields = array_keys($data);
        if ($id) { $set=implode(',',array_map(fn($field)=>"`$field`=?",$fields)); $stmt=$pdo->prepare("UPDATE `$table` SET $set WHERE id=?"); $stmt->execute([...array_values($data),$id]); $recordId=$id; }
        else { $cols='`'.implode('`,`',$fields).'`'; $marks=implode(',',array_fill(0,count($fields),'?')); $stmt=$pdo->prepare("INSERT INTO `$table` ($cols) VALUES ($marks)"); $stmt->execute(array_values($data)); $recordId=(int)$pdo->lastInsertId(); }
        if ($table !== 'projects' || empty($files['gallery']['name']) || !is_array($files['gallery']['name'])) return;
        $gallery = $files['gallery']; $sort = 0;
        foreach ($gallery['name'] as $index => $name) {
            $file=['name'=>$name,'type'=>$gallery['type'][$index] ?? '','tmp_name'=>$gallery['tmp_name'][$index] ?? '','error'=>$gallery['error'][$index] ?? UPLOAD_ERR_NO_FILE,'size'=>$gallery['size'][$index] ?? 0];
            $path = self::uploadImage($file); if (!$path) continue;
            $image = $pdo->prepare('INSERT INTO project_images (project_id,path,alt_text,sort_order) VALUES (?,?,?,?)');
            $image->execute([$recordId,$path,clean($input['title'] ?? 'Project image'),$sort++]);
        }
    }
    public static function delete(string $table,int $id):void{$allowed=['projects','products','services','blogs','testimonials','clients','orders','bookings','messages'];$pdo=db();if($pdo&&in_array($table,$allowed,true)){$s=$pdo->prepare("DELETE FROM `$table` WHERE id=?");$s->execute([$id]);}}
}
