    <style>
        /* Navbar */
        
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #bd399b;
            min-width: 160px;
            border-radius: 6px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.2);
            z-index: 99;
        }
        .dropdown-content a {
            color: white;
            padding: 10px 14px;
            display: block;
            text-decoration: none;
            /*border-bottom: 1px solid #222;*/
        }
        .dropdown-content a:hover { 
            background-color: #bd7fbb; 
            border-radius: 6px;
        }

        /* Layout */
        .dashboard-container {
            width: 90%;
            max-width: 1250px;
            margin: 100px auto;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 60% 40%;
            gap: 20px;
        }
        .card {
            background: rgba(255,255,255,0.9);
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(255,0,0,0.25);
            padding: 25px;
            line-height: 25px;
            color: black;
        }
        

        /* Tabs */
        .tabs-container { margin-top: 50px; }
        .tabs-header {
            display: flex;
            justify-content: center;
            gap: 50px;
            border-bottom: 2px solid rgba(255,0,0,0.2);
            margin-bottom: 25px;
        }
        .tab-item {
            font-weight: bold;
            text-transform: uppercase;
            color: #282828;
            padding: 10px 15px;
            cursor: pointer;
            position: relative;
            transition: 0.3s;
        }
        .tab-item:hover { color: #ff6363; }
        .tab-item.active {
            color: #ff3333;
        }
        .tab-item.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0; right: 0;
            height: 3px;
            background: #ff3333;
            box-shadow: 0 0 10px #ff3333;
        }

        /* Buttons */
        .cta-button {
            display: inline-block;
            background: #ff3333;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
            font-weight: bold;
        }
        .cta-button:hover { background: #ff5555; }

        /* Animations */
        #tab-content {
            min-height: 150px;
            transition: opacity 0.4s ease-in-out, filter 0.4s ease-in-out;
        }
        #tab-content.fade-out { opacity: 0.3; filter: blur(2px); }
        #tab-content.fade-in { opacity: 1; filter: blur(0); }

        /* Spinner */
        .loading {
            text-align: center;
            padding: 25px;
            color: #aaa;
            font-size: 16px;
        }
        .loading i {
            color: #ff3333;
            margin-right: 6px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { from {transform: rotate(0deg);} to {transform: rotate(360deg);} }

        @media (max-width: 850px) {
            .grid-2 { grid-template-columns: 1fr; }
            .tabs-header { flex-direction: column; align-items: center; gap: 10px; }
            
            .dropdown-content {
                display: none;
                position: absolute;
                right: 10px;
                background-color: #bd399b;
                min-width: 100px;
                border-radius: 6px;
                box-shadow: 0px 4px 12px rgba(0,0,0,0.2);
                z-index: 99;
            }
        }
    </style>