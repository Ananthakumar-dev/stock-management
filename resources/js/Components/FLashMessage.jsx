import { usePage } from "@inertiajs/react";
import { useEffect, useState } from "react";

const FlashMessage = () => {
    const { props } = usePage();
    const { flash } = props;
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        if (flash?.success || flash?.error) {
            setVisible(true);

            // Remove the flash message after 60 seconds
            const timer = setTimeout(() => {
                setVisible(false);
            }, 5000); // 5000 ms = 5 seconds

            return () => clearTimeout(timer); // Cleanup the timer
        }
    }, [flash]);

    if (!visible) return null;
    
    return (
        <div
            className={`p-4 mb-4 text-sm rounded text-center ${
                flash.success
                    ? "text-green-700 bg-green-100"
                    : "text-red-700 bg-red-100"
            }`}
        >
            {flash.success || flash.error}
        </div>
    );
};

export default FlashMessage;
