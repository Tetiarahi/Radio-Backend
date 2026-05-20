<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Radio Kiribati</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0F0F1A;
            --bg-secondary: #160D2D;
            --bg-card: rgba(255, 255, 255, 0.04);
            --border-card: rgba(255, 255, 255, 0.08);
            --brand-primary: #6B4EE6;
            --brand-secondary: #00D2FF;
            --text-primary: #FFFFFF;
            --text-secondary: rgba(255, 255, 255, 0.7);
            --text-muted: rgba(255, 255, 255, 0.4);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top left, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            width: 100%;
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px border var(--border-card);
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3),
                        0 0 40px rgba(107, 78, 230, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border-card);
            padding-bottom: 24px;
        }

        .logo-placeholder {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            border-radius: 16px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 28px;
            color: white;
            box-shadow: 0 8px 16px rgba(0, 210, 255, 0.3);
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #FFFFFF 30%, var(--brand-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .last-updated {
            font-size: 0.9rem;
            color: var(--brand-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        section {
            margin-bottom: 32px;
        }

        h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        h2::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 20px;
            background: var(--brand-secondary);
            border-radius: 3px;
        }

        p, ul {
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 400;
            margin-bottom: 16px;
        }

        ul {
            list-style-type: none;
            padding-left: 8px;
        }

        li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 8px;
        }

        li::before {
            content: "•";
            color: var(--brand-secondary);
            font-size: 1.2rem;
            position: absolute;
            left: 0;
            top: -2px;
        }

        a {
            color: var(--brand-secondary);
            text-decoration: none;
            transition: opacity 0.2s ease;
        }

        a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        footer {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid var(--border-card);
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        @media (max-width: 600px) {
            .container {
                padding: 24px;
                border-radius: 16px;
            }
            h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo-placeholder">RK</div>
            <h1>Radio Kiribati</h1>
            <div class="last-updated">Last Updated: May 2026</div>
        </header>

        <main>
            <section>
                <h2>Introduction</h2>
                <p>Welcome to <strong>Radio Kiribati</strong>, a mobile streaming and news application. Your privacy is extremely important to us. This Privacy Policy details how we handle information in connection with your use of our mobile application.</p>
            </section>

            <section>
                <h2>No Personal Data Collection</h2>
                <p>We do not collect, store, or share any personal identification information (such as names, email addresses, phone numbers, location data, or device identifiers) from users of this application.</p>
                <p>You can listen to our live streams and read our news articles freely without having to register or log in.</p>
            </section>

            <section>
                <h2>Required App Permissions</h2>
                <p>To deliver a smooth user experience, the Radio Kiribati app requests the following system permissions:</p>
                <ul>
                    <li><strong>Internet Access:</strong> Required to connect to our servers to stream live audio and fetch the latest news articles.</li>
                    <li><strong>Foreground Service:</strong> Required to allow the radio playback to continue playing smoothly in the background when the app is minimized or the screen is turned off.</li>
                </ul>
            </section>

            <section>
                <h2>Third-Party Services</h2>
                <p>Our app connects directly to the official Radio Kiribati backend server to retrieve streaming and article information. We do not use third-party analytics SDKs, advertising networks, or user-tracking platforms inside the application.</p>
            </section>

            <section>
                <h2>Changes to This Policy</h2>
                <p>We may update this Privacy Policy from time to time to reflect modifications in our application or services. We encourage you to review this page periodically to stay informed of any updates.</p>
            </section>

            <section>
                <h2>Contact Us</h2>
                <p>If you have any questions or feedback regarding this Privacy Policy, please feel free to reach out to us at:</p>
                <p>Email: <a href="mailto:itm@bpa.org.ki">itm@bpa.org.ki</a></p>
            </section>
        </main>

        <footer>
            &copy; 2026 Radio Kiribati. All Rights Reserved.
        </footer>
    </div>
</body>
</html>
