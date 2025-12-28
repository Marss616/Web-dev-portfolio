<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// Ensure CSRF token exists
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

// Handle logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in'], $_SESSION['admin_username']);
    header('Location: admin.php');
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    if (!hash_equals($csrf, $_POST['csrf'] ?? '')) {
        $login_error = 'Invalid CSRF token.';
    } else {
        $user = trim($_POST['username'] ?? '');
        $pass = trim($_POST['password'] ?? '');

        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $user]);
        $row = $stmt->fetch();

        if ($row && password_verify($pass, $row['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $row['username'];
            header('Location: admin.php');
            exit;
        } else {
            $login_error = 'Invalid username or password.';
        }
    }
}

// Protect admin area
$isLoggedIn = !empty($_SESSION['admin_logged_in']);

$message = $message ?? null;

/**
 * Small helper to handle image upload.
 * Returns: [string|null $path, string|null $errorMessage]
 */
function handleImageUpload(string $fieldName, string $uploadDirFs, string $uploadDirWeb): array
{
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return [null, null];
    }

    $tmpName  = $_FILES[$fieldName]['tmp_name'];
    $origName = $_FILES[$fieldName]['name'] ?? $fieldName;

    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $ext = preg_replace('/[^a-z0-9]/', '', $ext) ?: 'jpg';

    $fileName = 'project_' . time() . '_' . $fieldName . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destFs   = $uploadDirFs . $fileName;

    if (move_uploaded_file($tmpName, $destFs)) {
        return [$uploadDirWeb . $fileName, null];
    }
    return [null, "Failed to move uploaded file for {$fieldName}"];
}

// ========= SIMPLE FILE UPLOAD DIR SETUP (used by create & update) =========
$uploadDirFs  = __DIR__ . '/uploads/'; // filesystem path
$uploadDirWeb = 'uploads/';            // path used in <img src="...">

if (!is_dir($uploadDirFs)) {
    @mkdir($uploadDirFs, 0755, true);
}

// DEBUG: see exactly what admin form sends (optional)
if (!empty($_FILES)) {
    file_put_contents(__DIR__ . '/upload_debug.log', print_r($_FILES, true), FILE_APPEND);
}

// Handle new project submission
if (
    $isLoggedIn &&
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'create'
) {
    if (!hash_equals($csrf, $_POST['csrf'] ?? '')) {
        $message = 'Invalid CSRF token.';
    } else {
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $tags        = trim($_POST['tags'] ?? '');
        $link        = trim($_POST['link'] ?? '');

        if ($title === '' || $description === '' || $tags === '') {
            $message = 'Title, description, and tags are required.';
        } else {
            // default to nulls
            $img1Path = null;
            $img2Path = null;
            $img3Path = null;

            // --- Image 1 ---
            [$img1Path, $err1] = handleImageUpload('img1', $uploadDirFs, $uploadDirWeb);
            if ($err1) {
                $message = $err1;
            }

            // --- Image 2 ---
            [$img2Path, $err2] = handleImageUpload('img2', $uploadDirFs, $uploadDirWeb);
            if ($err2 && !$message) {
                $message = $err2;
            }

            // --- Image 3 ---
            [$img3Path, $err3] = handleImageUpload('img3', $uploadDirFs, $uploadDirWeb);
            if ($err3 && !$message) {
                $message = $err3;
            }

            // 🔹 FALLBACK: placeholder if img1 wasn't uploaded
            $img1Db = $img1Path ?? 'uploads/placeholder.png';
            $img2Db = $img2Path;
            $img3Db = $img3Path;

            // ========= INSERT INTO DB =========
            $stmt = $pdo->prepare(
                'INSERT INTO projects (title, description, tags, link, img1, img2, img3)
                 VALUES (:title, :description, :tags, :link, :img1, :img2, :img3)'
            );
            $stmt->execute([
                ':title'       => $title,
                ':description' => $description,
                ':tags'        => $tags,
                ':link'        => $link !== '' ? $link : null,
                ':img1'        => $img1Db,
                ':img2'        => $img2Db,
                ':img3'        => $img3Db,
            ]);

            if (!$message) {
                $message = 'Project added.';
            }
        }
    }
}

// Handle project update (EDIT)
if (
    $isLoggedIn &&
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'update'
) {
    if (!hash_equals($csrf, $_POST['csrf'] ?? '')) {
        $message = 'Invalid CSRF token.';
    } else {
        $id          = (int)($_POST['id'] ?? 0);
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $tags        = trim($_POST['tags'] ?? '');
        $link        = trim($_POST['link'] ?? '');

        if ($id <= 0) {
            $message = 'Invalid project ID.';
        } elseif ($title === '' || $description === '' || $tags === '') {
            $message = 'Title, description, and tags are required.';
        } else {
            // Fetch existing project to preserve current images if no new upload
            $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$existing) {
                $message = 'Project not found.';
            } else {
                // Start with existing values
                $img1Path = $existing['img1'] ?? null;
                $img2Path = $existing['img2'] ?? null;
                $img3Path = $existing['img3'] ?? null;

                // If new files are uploaded, overwrite paths
                [$newImg1, $err1] = handleImageUpload('img1', $uploadDirFs, $uploadDirWeb);
                if ($newImg1) {
                    $img1Path = $newImg1;
                }
                if ($err1 && !$message) {
                    $message = $err1;
                }

                [$newImg2, $err2] = handleImageUpload('img2', $uploadDirFs, $uploadDirWeb);
                if ($newImg2) {
                    $img2Path = $newImg2;
                }
                if ($err2 && !$message) {
                    $message = $err2;
                }

                [$newImg3, $err3] = handleImageUpload('img3', $uploadDirFs, $uploadDirWeb);
                if ($newImg3) {
                    $img3Path = $newImg3;
                }
                if ($err3 && !$message) {
                    $message = $err3;
                }

                // 🔹 Ensure img1 never ends up empty (keep existing or placeholder)
                if (empty($img1Path)) {
                    $img1Path = 'uploads/placeholder.png';
                }

                $stmt = $pdo->prepare(
                    'UPDATE projects
                     SET title = :title,
                         description = :description,
                         tags = :tags,
                         link = :link,
                         img1 = :img1,
                         img2 = :img2,
                         img3 = :img3
                     WHERE id = :id'
                );
                $stmt->execute([
                    ':id'          => $id,
                    ':title'       => $title,
                    ':description' => $description,
                    ':tags'        => $tags,
                    ':link'        => $link !== '' ? $link : null,
                    ':img1'        => $img1Path,
                    ':img2'        => $img2Path,
                    ':img3'        => $img3Path,
                ]);

                if (!$message) {
                    $message = 'Project updated.';
                }
            }
        }
    }
}

// Determine if we're editing a specific project
$editProject = null;
if ($isLoggedIn && isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    if ($editId > 0) {
        $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $editId]);
        $editProject = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        if (!$editProject) {
            $message = $message ?: 'Project not found.';
        }
    }
}

// Fetch projects for listing
$projects = [];
if ($isLoggedIn) {
    $projects = $pdo->query('SELECT * FROM projects ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin – Projects</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      body { font-family: system-ui, sans-serif; max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
      form { margin-bottom: 2rem; padding: 1rem; border: 1px solid #ccc; border-radius: 8px; }
      label { display:block; margin-top: 0.5rem; font-size: 0.9rem; }
      input[type=text], input[type=password], textarea {
        width: 100%; padding: 0.5rem; margin-top: 0.25rem;
      }
      input[type=file] { margin-top: 0.25rem; }
      .btn { display:inline-block; padding:0.5rem 1rem; margin-top:0.75rem; cursor:pointer; }
      .error { color: #b00020; }
      .msg { color: #006600; }
      table { width:100%; border-collapse: collapse; margin-top: 1rem; }
      th, td { border:1px solid #ddd; padding:0.4rem; font-size: 0.85rem; vertical-align: top; }
      img.thumb { max-width: 60px; max-height: 60px; display:block; margin-bottom: 0.25rem; border-radius: 4px; }
    </style>
</head>
<body>
<h1>Admin – Projects</h1>

<?php if (!$isLoggedIn): ?>
  <h2>Login</h2>
  <?php if (!empty($login_error)): ?>
    <p class="error"><?= htmlspecialchars($login_error) ?></p>
  <?php endif; ?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <input type="hidden" name="action" value="login">
    <label>Username
      <input type="text" name="username" autocomplete="username">
    </label>
    <label>Password
      <input type="password" name="password" autocomplete="current-password">
    </label>
    <button class="btn" type="submit">Login</button>
  </form>

<?php else: ?>
  <p>Logged in as <strong><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></strong> –
     <a href="?logout=1">Logout</a></p>

  <?php if (!empty($message)): ?>
    <p class="msg"><?= htmlspecialchars($message) ?></p>
  <?php endif; ?>

  <?php if ($editProject): ?>
    <h2>Edit project #<?= (int)$editProject['id'] ?></h2>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= (int)$editProject['id'] ?>">

      <label>Title
        <input type="text" name="title" required
               value="<?= htmlspecialchars($editProject['title'] ?? '') ?>">
      </label>

      <label>Description
        <textarea name="description" rows="3" required><?= htmlspecialchars($editProject['description'] ?? '') ?></textarea>
      </label>

      <label>Tags (comma-separated)
        <input type="text" name="tags" required
               value="<?= htmlspecialchars($editProject['tags'] ?? '') ?>">
      </label>

      <label>Link (optional)
        <input type="text" name="link"
               value="<?= htmlspecialchars($editProject['link'] ?? '') ?>">
      </label>

      <p>Current images:</p>
      <div>
        <?php if (!empty($editProject['img1'])): ?>
          <img class="thumb" src="<?= htmlspecialchars($editProject['img1']) ?>" alt="">
        <?php endif; ?>
        <?php if (!empty($editProject['img2'])): ?>
          <img class="thumb" src="<?= htmlspecialchars($editProject['img2']) ?>" alt="">
        <?php endif; ?>
        <?php if (!empty($editProject['img3'])): ?>
          <img class="thumb" src="<?= htmlspecialchars($editProject['img3']) ?>" alt="">
        <?php endif; ?>
      </div>

      <label>Replace Image 1 (optional)
        <input type="file" name="img1" accept="image/*">
      </label>

      <label>Replace Image 2 (optional)
        <input type="file" name="img2" accept="image/*">
      </label>

      <label>Replace Image 3 (optional)
        <input type="file" name="img3" accept="image/*">
      </label>

      <button class="btn" type="submit">Update project</button>
      <a href="admin.php" class="btn">Cancel</a>
    </form>
  <?php endif; ?>

  <h2>Add new project</h2>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
    <input type="hidden" name="action" value="create">

    <label>Title
      <input type="text" name="title" required>
    </label>

    <label>Description
      <textarea name="description" rows="3" required></textarea>
    </label>

    <label>Tags (comma-separated)
      <input type="text" name="tags" placeholder="React, Django, Docker" required>
    </label>

    <label>Link (optional)
      <input type="text" name="link" placeholder="https://github.com/... or https://...">
    </label>

    <label>Image 1 (optional)
      <input type="file" name="img1" accept="image/*">
    </label>

    <label>Image 2 (optional)
      <input type="file" name="img2" accept="image/*">
    </label>

    <label>Image 3 (optional)
      <input type="file" name="img3" accept="image/*">
    </label>

    <button class="btn" type="submit">Save project</button>
  </form>

  <h2>Existing projects</h2>
  <?php if (!$projects): ?>
    <p>No projects yet.</p>
  <?php else: ?>
    <table>
      <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Images</th>
        <th>Tags</th>
        <th>Link</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($projects as $p): ?>
        <tr>
          <td><?= (int)$p['id'] ?></td>
          <td><?= htmlspecialchars($p['title']) ?></td>
          <td>
            <?php if (!empty($p['img1'])): ?>
              <img class="thumb" src="<?= htmlspecialchars($p['img1']) ?>" alt="">
            <?php endif; ?>
            <?php if (!empty($p['img2'])): ?>
              <img class="thumb" src="<?= htmlspecialchars($p['img2']) ?>" alt="">
            <?php endif; ?>
            <?php if (!empty($p['img3'])): ?>
              <img class="thumb" src="<?= htmlspecialchars($p['img3']) ?>" alt="">
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($p['tags']) ?></td>
          <td>
            <?php if (!empty($p['link'])): ?>
              <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank" rel="noopener">View</a>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($p['created_at'] ?? '') ?></td>
          <td>
            <a href="admin.php?edit=<?= (int)$p['id'] ?>">Edit</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

<?php endif; ?>

</body>
</html>
