import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { HiArrowLeft } from "react-icons/hi";

export default function PortalFrame({ src, title, hideBackButton }) {
  const navigate = useNavigate();
  const storageKey = "portal_url_" + title.replace(/\s+/g, "_");

  const allowedPrefix = (() => {
    try {
      const parts = new URL(src).pathname.split("/").filter(Boolean);
      return "/" + parts.slice(0, 3).join("/");
    } catch {
      return "/";
    }
  })();

  const [iframeSrc, setIframeSrc] = useState(() => {
    const saved = sessionStorage.getItem(storageKey);
    if (saved) {
      try {
        const savedPath = new URL(saved).pathname;
        if (savedPath.startsWith(allowedPrefix)) return saved;
      } catch {}
      sessionStorage.removeItem(storageKey);
    }
    return src;
  });

  useEffect(() => {
    const handler = (e) => {
      if (e.data?.action === "navigate-home") {
        navigate("/");
      } else if (e.data?.action === "iframe-nav" && e.data?.url) {
        const status = e.data.status ?? 200;

        if (status >= 400) {
          // Error page — clear bad URL and reset iframe to the safe default
          sessionStorage.removeItem(storageKey);
          setIframeSrc(src);
          return;
        }

        // Only persist successful navigations within this portal's path
        try {
          const newPath = new URL(e.data.url).pathname;
          if (newPath.startsWith(allowedPrefix)) {
            sessionStorage.setItem(storageKey, e.data.url);
          }
        } catch {}
      }
    };
    window.addEventListener("message", handler);
    return () => window.removeEventListener("message", handler);
  }, [navigate, storageKey, allowedPrefix, src]);

  return (
    <div style={{ position: "fixed", inset: 0, zIndex: 9999, background: "#000" }}>
      {!hideBackButton && (
        <button
          onClick={() => navigate("/")}
          style={{
            position: "absolute",
            top: 12,
            left: 12,
            zIndex: 10000,
            display: "flex",
            alignItems: "center",
            gap: 6,
            padding: "6px 14px",
            borderRadius: 8,
            background: "rgba(0,0,0,0.55)",
            color: "#fff",
            border: "1px solid rgba(255,255,255,0.25)",
            cursor: "pointer",
            fontSize: 13,
            fontWeight: 600,
            backdropFilter: "blur(6px)",
          }}
        >
          <HiArrowLeft /> Back to Main
        </button>
      )}
      <iframe
        key={iframeSrc}
        src={iframeSrc}
        title={title}
        style={{ width: "100%", height: "100%", border: "none" }}
        allow="same-origin"
      />
    </div>
  );
}
