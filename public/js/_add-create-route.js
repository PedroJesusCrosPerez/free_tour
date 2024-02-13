    function format(id) {
        switch (id) {
            case "1": return 'Lunes';
            case "2": return 'Martes';
            case "3": return 'Miércoles';
            case "4": return 'Jueves';
            case "5": return 'Viernes';
            case "6": return 'Sábado';
            case "7": return 'Domingo'
            default: return 'error en switch 216589413';
        }
    }

    function addTable(data) {
        var tr = $('<tr>');
        var tdPeriod = $('<td>');
        tdPeriod.html(data.date_start + ' - ' + data.date_end);
        var tdTime_start = $('<td>');
        tdTime_start.html(data.time_start);
        var tdPattern = $('<td>');
        if ( Array.isArray(data.patternf) ) {
            tdPattern.html(data.patternf.join(', '));
        } else {
            tdPattern.html(data.patternf);
        }
        var tdGuide = $('<td>');
        tdGuide.html(data.guide.name);

        tr.append(tdPeriod);
        tr.append(tdTime_start);
        tr.append(tdPattern);
        tr.append(tdGuide);

        $('table[name="programation"]').append(tr);
    }

    const programation = [];
    function addButtonProgram() {
        var data = takeProgramationData();
        addTable(data);
        
        // if (localStorage.getItem("programation") !== null) {
        //     var programation = localStorage.getItem(JSON.parse('programation'));
        //     programation.push(data);
        //     localStorage.setItem('programation', JSON.stringify(programation));
        // } else {
        //     localStorage.setItem('programation', JSON.stringify([]));
        // }
        
        programation.push(data);
        console.log(programation);
    }

    function programAddProgramation() {
        // Program buttón programation "AÑADIR"
        $('#add').on('click', addButtonProgram);
    }


    // function createButtonProgram() {
    //     // console.log(takeData());
    // }

    // function programCreateProgramation() {
    //     // Program buttón programation "CREATE"
    //     $('#create').on('click', createButtonProgram);
    // }
